<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Individual;
use App\Renewal;
use App\RenewalRun;
use App\RenewalRunEmail;
use App\RenewalRunEntity;
use App\Services\Sparkpost;

class SendRenewalEmailsController extends Controller
{
    protected $sparkpost;

    public function __construct()
    {
        $this->sparkpost = new Sparkpost;
    }

    /**
     * Sends renewal emails to specified individuals.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = request()->validate([
            'renewal_run_id' => 'required|numeric|exists:renewal_runs,id',
            'individual_id' => 'nullable|numeric|exists:individuals,id',
            'is_reminder_email' => 'required|boolean',
            'send_to_test_email' => 'required|boolean',
            'test_email' => 'required_if:send_to_test_email,1|nullable|email',
        ]);

        if ($data['send_to_test_email']) {
            $this->sparkpost->to($data['test_email']);
        }

        $renewalRun = RenewalRun::find(request('renewal_run_id'));

        $this->sparkpost->template($this->decideTemplateId());

        $individuals = $this->getIndividuals($renewalRun);

        return $this->sendEmailTo($individuals);
    }

    /**
     * Returns the id of the template to be used for the email.
     *
     * @return bool Whether id or string of the template should be returned
     * @return string
     */
    private function decideTemplateId($requireId = false)
    {
        if (request('is_reminder_email')) {
            return $requireId ? 2 : 'jss-renewal-reminder';
        }

        return $requireId ? 1 : 'jss-renewal';
    }

    /**
     * Sends renewal emails to specified individuals.
     *
     * @param \Illuminate\Support\Collection
     * @return Illuminate\Http\Response
     */
    private function sendEmailTo($individuals)
    {
        if ($this->sparkpost->send($individuals)) {
            $this->recordRenewalRunEmail($individuals->first());

            return [
                'type' => 'success',
                'message' => 'Email(s) sent successfully.',
            ];
        }

        return [
            'type' => 'error',
            'message' => 'Email(s) could not be sent. An error occurred.',
        ];
    }

    /**
     * Records the renewal run email, if required.
     *
     * @param \App\Individual
     * @return void
     */
    private function recordRenewalRunEmail($individual)
    {
        if (! request('send_to_test_email')) {
            $data = [
                'renewal_run_id' => request('renewal_run_id'),
                'sparkpost_template_id' => $this->decideTemplateId($requireId = true),
                'sent_at' => now(),
            ];

            if (request('individual_id')) {
                $data['individual_id'] = $individual->id;
            }

            RenewalRunEmail::create($data);
        }
    }

    /**
     * Returns the individuals of the renewal emails.
     *
     * @param App\RenewalRun
     * @return \Illuminate\Support\Collection
     */
    private function getIndividuals($renewalRun)
    {
        if (request('individual_id')) {
            $individual = Individual::with('membership')->findOrFail(request('individual_id'));

            return collect([$individual]);
        }

        $individualIds = RenewalRunEntity::where('renewal_run_id', $renewalRun->id)
            ->whereNotIn('individual_id', $this->getSkipIndividualIds($renewalRun))
            ->when(request('send_to_test_email'), function ($query) {
                return $query->limit(20);
            })
            ->get(['individual_id'])
        ;

        return Individual::with('membership')->whereIn('id', $individualIds)->get();
    }

    /**
     * Returns the individuals ids that should not be sent reminder emails, if any.
     *
     * @param App\RenewalRun
     * @return array
     */
    private function getSkipIndividualIds($renewalRun)
    {
        if (request('is_reminder_email')) {
            return Renewal::where('renewal_run_id', $renewalRun->id)
                ->get(['individual_id'])
                ->pluck('individual_id')
            ;
        }

        return [];
    }
}
