<?php

namespace App\Services;

use App\IndividualMembership;
use App\SparkpostTransmission;
use GuzzleHttp\Client;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Illuminate\Support\Facades\Log;
use SparkPost\SparkPost as SparkPostService;

class Sparkpost
{
    protected $to = null;

    protected $templateId = null;

    protected $description = '';

    protected $options = [];

    protected $content = [];

    protected $bcc = [];

    /**
     * Sets the receiver of the email(s).
     *
     * @return $this
     */
    public function to($email)
    {
        $this->to = $email;

        return $this;
    }

    /**
     * Sets the template id for the email(s).
     *
     * @param string Template id
     * @return $this
     */
    public function template($templateId)
    {
        $this->templateId = $templateId;

        return $this;
    }

    /**
     * Sets the description for the email(s).
     *
     * @param string description
     * @return $this
     */
    public function description($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Sets the options for the email(s).
     *
     * @param array options
     * @return $this
     */
    public function options($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Sets the content for the email(s).
     *
     * @param array content
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Sets the bcc details for the email(s).
     *
     * @param array bcc
     * @return $this
     */
    public function bcc($bcc)
    {
        $this->bcc = $bcc;

        return $this;
    }

    /**
     * Sparkpost transmission (renewal mail sending).
     *
     * @param \Illuminate\Support\Collection
     * @return mixed
     **/
    public function send($individuals)
    {
        if (! config('services.sparkpost.secret')) {
            return;
        }

        $sparky = $this->getSparkPostInstance();

        $postData = [
            'content' => $this->getContent(),
            'recipients' => $this->getRecipients($individuals),
        ];

        if ($this->options) {
            $postData['options'] = $this->options;
        }

        if ($this->description) {
            $postData['description'] = $this->description;
        }

        if ($this->bcc) {
            $postData['bcc'] = $this->bcc;
        }

        $promise = $sparky->transmissions->post($postData);

        try {
            $response = $promise->wait();
            $responseBody = $response->getBody();

            Log::channel('sparkpost')->info('Sparkpost Transmission call output', [
                'response_code' => $response->getStatusCode(),
                'response_body' => $responseBody,
            ]);

            if ($response->getStatusCode() == 200) {
                SparkpostTransmission::create([
                    'id' => $responseBody['results']['id'],
                    'rejected_recipients' => $responseBody['results']['total_rejected_recipients'],
                    'accepted_recipients' => $responseBody['results']['total_accepted_recipients'],
                ]);

                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::channel('sparkpost')->error('Sparkpost Transmission call error', [
                'error_code' => $e->getCode(),
                'error_message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Fetches message events from sparkpost.
     *
     * @return array
     **/
    public function messageEvents()
    {
        if (! config('services.sparkpost.secret')) {
            return;
        }

        $sparky = $this->getSparkPostInstance();

        $promise = $sparky->request('GET', 'message-events', [
            'per_page' => 10000,
            'timezone' => 'Australia/Perth',
            'from' => now()->subMinutes(30)->format('Y-m-d\TH:i'),
            'to' => now()->addMinute()->format('Y-m-d\TH:i'), // To avoid API error
        ]);

        return $this->processResponse($promise, $logMessage = 'message events');
    }

    /**
     * Fetches and returns template details from sparkpost.
     *
     * @param string Id of the template
     * @return array
     **/
    public function fetchTemplateDetails($templateId)
    {
        if (! config('services.sparkpost.secret')) {
            return;
        }

        $sparky = $this->getSparkPostInstance();

        $promise = $sparky->request('GET', 'templates/' . $templateId);

        return $this->processResponse($promise, $logMessage = 'template', $templateId);
    }

    /**
     * Creates and returns a new sparkpost service instance.
     *
     * @param object Sparkpost promise
     * @param string Message for log file
     * @param string Id of the template
     * @return mixed
     **/
    private function processResponse($promise, $logMessage, $templateId = null)
    {
        try {
            $response = $promise->wait();

            $responseBody = $response->getBody();

            if ($logMessage != 'template') {
                Log::channel('sparkpost')->info('Sparkpost ' . $logMessage . ' data', [
                    'response_code' => $response->getStatusCode(),
                    'response_body' => $responseBody,
                ]);
            } else {
                Log::channel('sparkpost')->info('Sparkpost template details fetched', [
                    'template_id' => $templateId
                ]);
            }

            return $responseBody;
        } catch (\Exception $e) {
            Log::channel('sparkpost')->error('Sparkpost ' . $logMessage . ' call error', [
                'error_code' => $e->getCode(),
                'error_message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Creates and returns a new sparkpost service instance.
     *
     * @return \SparkPost\SparkPost
     **/
    private function getSparkPostInstance()
    {
        $httpClient = new GuzzleAdapter(new Client());

        return new SparkPostService($httpClient, ['key' => config('services.sparkpost.secret')]);
    }

    /**
     * Returns the content/template of the email.
     *
     * @return array
     **/
    private function getContent()
    {
        if ($this->templateId) {
            return ['template_id' => $this->templateId];
        }

        return $this->content;
    }

    /**
     * Returns recipients with email substitution data.
     *
     * @param \Illuminate\Support\Collection
     * @return array
     **/
    private function getRecipients($individuals)
    {
        return $individuals->map(function ($individual) {
            return [
                'address' => $this->getReceiverDetailsFor($individual),
                'substitution_data' => $this->getRenewalSubstitutionDataFor($individual),
                'metadata' => $this->getRenewalMetadataFor($individual),
            ];
        })->toArray();
    }

    /**
     * Returns receiver name and email for transmission.
     *
     * @param App\Individual
     * @return array
     **/
    private function getReceiverDetailsFor($individual)
    {
        if ($this->to) {
            return [
                'name' => config('app.name') . ' User',
                'email' => $this->to,
            ];
        }

        return [
            'name' => $individual->getName(),
            'email' => $individual->email_address,
        ];
    }

    /**
     * Returns substitution data array for transmission.
     *
     * @param App\Individual
     * @return array
     **/
    private function getRenewalSubstitutionDataFor($individual)
    {
        $data = [
            'IndividualId' => $individual->id,
            'MembershipNumber' => $individual->getMembershipNumber(),
            'FirstName' => $individual->first_name,
            'LastName' => $individual->surname,
        ];

        if (in_array($this->templateId, ['jss-renewal', 'jss-renewal-reminder'])) {
            $data['LinkURL'] = getRenewalLink($individual->id);
        }

        if (
            in_array($this->templateId, [
                IndividualMembership::getRegisterTemplateId(),
                IndividualMembership::getPasswordResetTemplateId()
            ])
        ) {
            $data['LinkURL'] = $individual->getChoosePasswordLink();
        }

        return $data;
    }

    /**
     * Returns metadata array for transmission.
     *
     * @param App\Individual
     * @return array
     **/
    private function getRenewalMetadataFor($individual)
    {
        return [
            'IndividualId' => $individual->id,
        ];
    }
}
