<?php

namespace App\Http\Controllers;

use App\CalendarEvent;
use App\Http\Requests\MembershipNumberValidation;
use App\Http\Requests\UpdateMember;
use App\Individual;
use App\IndividualMembership;
use App\IndividualSsaa;
use App\Services\Browsershot;
use App\Services\Sparkpost;
use App\State;
use App\Suburb;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use ReCaptcha\ReCaptcha;

class RegisterController extends Controller
{
    /**
     * Displays the front page.
     *
     * @return \Illuminate\Http\Response
     **/
    public function index()
    {
        if (
            ! auth()->guard('member')->check() &&
            request()->has('choose-password') &&
            request('choose-password') == true
        ) {
            return $this->choosePassword();
        }

        $suburbs = Suburb::getSelect2OptionsFor($stateId = 4);
        $states = State::getList();
        $currentPage = 'login';
        $memberDetails = [];
        $attendances = [];

        if (Auth::guard('member')->check()) {
            $currentPage = 'member-details';
            $memberDetails = $this->getMemberDetails(auth()->guard('member')->user()->id);
            $attendances = $this->getAttendances(auth()->guard('member')->user()->id);
        }

        return view('front.auth.layout', compact('suburbs', 'states', 'currentPage', 'memberDetails', 'attendances'));
    }

    /**
     * Sends an email with link to set the password.
     *
     * @return \Illuminate\Http\JsonResponse
     **/
    public function register(MembershipNumberValidation $request)
    {
        $member = IndividualSsaa::query()
            ->with('individual')
            ->where('ssaa_number', request('membership_number'))
            ->first()
        ;

        $this->checkRequestDetails($member);

        return $this->sendEmailTo($member->individual, IndividualMembership::getRegisterTemplateId());
    }

    /**
     * Displays the page to set password.
     *
     * @return \Illuminate\Http\JsonResponse
     **/
    public function choosePassword()
    {
        if (! request()->hasValidSignature()) {
            abort(401);
        }

        $member = IndividualSsaa::query()
            ->with('individual')
            ->where('ssaa_number', request('membership-number'))
            ->where('individual_id', request('individual-id'))
            ->first()
        ;

        if (! $member) {
            abort(404);
        }

        $suburbs = Suburb::getSelect2OptionsFor($stateId = 4);
        $states = State::getList();
        $currentPage = 'choose-password';
        $memberDetails = $this->getMemberDetails($member->individual_id);
        $attendances = $this->getAttendances($member->individual_id);

        return view('front.auth.layout', compact('suburbs', 'states', 'currentPage', 'memberDetails', 'attendances'));
    }

    /**
     * Updates the password of the member.
     *
     * @return \Illuminate\Http\JsonResponse
     **/
    public function changePassword()
    {
        request()->validate([
            'membership_number' => 'required|numeric|exists:individual_ssaas,ssaa_number',
            'password' => 'required|string|confirmed|min:8',
            'google_recaptcha_token' => 'required|string',
        ]);

        $this->verifyGoogleReCaptcha();

        $member = IndividualSsaa::query()
            ->with('individual')
            ->where('ssaa_number', request('membership_number'))
            ->first()
        ;

        if (! $member) {
            abort(422, 'Incorrect Membership Number.');
        }

        $member->individual->password = bcrypt(request('password'));
        $member->individual->save();

        Auth::guard('member')->login($member->individual, true);

        return response()->json([
            'data' => [
                'attendances' => $this->getAttendances($member->individual_id),
                'memberDetails' => $this->getMemberDetails($member->individual_id),
            ],
        ]);
    }

    /**
     * Login a member.
     *
     * @return \Illuminate\Http\JsonResponse
     **/
    public function login()
    {
        request()->validate([
            'membership_number' => 'required|numeric|exists:individual_ssaas,ssaa_number',
            'password' => 'required|string',
            'google_recaptcha_token' => 'required|string',
        ]);

        $this->verifyGoogleReCaptcha();

        $member = IndividualSsaa::query()
            ->with('individual')
            ->where('ssaa_number', request('membership_number'))
            ->first()
        ;

        if (! $member || ! $member->individual || ! $member->individual->password) {
            abort(422, 'Invalid request.');
        }

        if (Hash::check(request('password'), $member->individual->password)) {
            Auth::guard('member')->login($member->individual, true);

            return response()->json([
                'data' => [
                    'attendances' => $this->getAttendances($member->individual_id),
                    'memberDetails' => $this->getMemberDetails($member->individual_id),
                ],
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Credentials do not match.'
        ], Response::HTTP_FORBIDDEN);
    }

    /**
     * Returns the member details.
     *
     * @param int $individualId
     * @return \Illuminate\Support\Collection
     **/
    private function getMemberDetails($individualId)
    {
        $individual = Individual::with(['membership', 'ssaa', 'disciplines'])->find($individualId);
        $otherInformation = [];

        if ($individual->pension_card) {
            array_push($otherInformation, 'Pensioner');
        }

        if ($individual->is_committee_member) {
            array_push($otherInformation, 'Committee Member');
        }

        if ($individual->is_club_lifetime_member) {
            array_push($otherInformation, 'Lifetime Member');
        }

        return collect([
            'id' => $individual->id,
            'first_name' => $individual->first_name,
            'middle_name' => $individual->middle_name,
            'surname' => $individual->surname,
            'occupation' => $individual->occupation,
            'email_address' => $individual->email_address,
            'phone_number' => $individual->phone_number,
            'mobile_number' => $individual->mobile_number,
            'address_line_1' => $individual->address_line_1,
            'address_line_2' => $individual->address_line_2,
            'ssaa_number' => $individual->ssaa->ssaa_number,
            'ssaa_expiry' => $individual->ssaa->ssaa_expiry,
            'membership_number' => optional(optional($individual)->membership)->membership_number,
            'suburb' => [
                'id' => $individual->suburb_id,
                'text' => $individual->getSuburb(),
            ],
            'state_id' => $individual->state_id,
            'post_code' => $individual->post_code,
            'other_information' => implode(', ', $otherInformation),
            'disciplines_text' => $this->getDisciplineText($individual->disciplines),
            'gender' => $individual->gender_id == 1 ? 'Male' : 'Female',
            'membership_expiry' => optional($individual->membership)->expiry ? Carbon::createFromFormat('Y-m-d', $individual->membership->expiry)->format('jS M Y') : '',
            'date_of_birth' => Carbon::createFromFormat('Y-m-d', $individual->date_of_birth)->format('jS M Y'),
        ]);
    }

    /**
     * Returns the text of the member disciplines.
     *
     * @param \Illuminate\Support\Collection $disciplines
     * @return string
     **/
    private function getDisciplineText($disciplines)
    {
        $disciplineTexts = [];

        foreach ($disciplines as $discipline) {
            $text = $discipline->label;

            if ($discipline->pivot->is_lifetime_member) {
                $text .= ' (Life member)';
            }

            array_push($disciplineTexts, $text);
        }

        return implode(', ', $disciplineTexts);
    }

    /**
     * Updates specified member details.
     *
     * @return \Illuminate\Http\JsonResponse
     **/
    public function updateMemberDetails(UpdateMember $request)
    {
        $this->verifyGoogleReCaptcha();

        $individual = Individual::with('ssaa')->find(request('id'));

        $individual->updateFromRequest();

        return response()->json([
            'type' => 'success',
            'message' => 'Member details updated successfully.'
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\JsonResponse
     **/
    public function logout()
    {
        Auth::guard('member')->logout();

        request()->session()->invalidate();

        return response()->json([
            'type' => 'success',
            'message' => 'Logged out successfully.'
        ]);
    }

    /**
     * Sends the email with reset password link.
     *
     * @return \Illuminate\Http\JsonResponse
     **/
    public function forgotPassword(MembershipNumberValidation $request)
    {
        $member = IndividualSsaa::query()
            ->with('individual')
            ->where('ssaa_number', request('membership_number'))
            ->first()
        ;

        $this->checkRequestDetails($member, $passwordCheck = false);

        return $this->sendEmailTo($member->individual, IndividualMembership::getPasswordResetTemplateId());
    }

    /**
     * Validates the member details. Aborts the request in case of an issue.
     *
     * @param \App\IndividualSsaa $member
     * @param bool Whether existing password check should be done
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     **/
    private function checkRequestDetails($member, $passwordCheck = true)
    {
        $this->verifyGoogleReCaptcha();

        if (! $member) {
            abort(422, 'Incorrect Membership Number.');
        }

        if ($passwordCheck && $member->individual->password) {
            abort(422, 'You have already registered. You may click on \'Lost Password\' if you forgot it.');
        }

        if (! $member->individual && ! $member->individual->email_address) {
            abort(422, 'Some of the details of your account are missing. Please contact admin.');
        }
    }

    /**
     * Sends an email to the specified individual.
     *
     * @param \App\Individual $individual
     * @param string $templateId sparkpost template id
     * @return \Illuminate\Http\JsonResponse
     **/
    private function sendEmailTo($individual, $templateId)
    {
        $sparkpost = new Sparkpost;
        $sparkpost->template($templateId);

        if ($sparkpost->send(collect([$individual]))) {
            return response()->json([
                'type' => 'success',
                'message' => 'Email(s) sent successfully.'
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Email(s) could not be sent. An error occurred.'
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Verifies the google recaptcha response and aborts the request if score is low.
     *
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     **/
    public function verifyGoogleReCaptcha()
    {
        $reCaptcha = new ReCaptcha(config('services.google.recaptcha_secret_key'));
        $reCaptchaResponse = $reCaptcha
            ->setScoreThreshold(0.5)
            ->verify(request('google_recaptcha_token'), request()->getClientIp())
        ;

        Log::channel('google')->info($reCaptchaResponse->toArray());

        if (! $reCaptchaResponse->isSuccess()) {
            abort(422, 'Our request verification failed. Please try again from other browser.');
        }
    }

    /**
     * Returns the list of the attendances
     *
     * @param int $individualId
     * @return \Illuminate\Support\Collection
     **/
    private function getAttendances($individualId)
    {
        $calendarEvents = CalendarEvent::query()
            ->withoutGlobalScope('user')
            ->select('id', 'event_date', 'score_type', 'discipline_id')
            ->with(['discipline:id,label', 'scores' => function ($query) use ($individualId) {
                $query->where('individual_id', $individualId);
            }])
            ->whereHas('scores', function ($query) use ($individualId) {
                $query->where('individual_id', $individualId);
            })
            ->where('is_attendance_tracked', true)
            ->where('is_finalised', true)
            ->orderBy('event_date', 'asc')
            ->get()
        ;

        $attendances = [];

        foreach ($calendarEvents as $calendarEvent) {
            foreach ($calendarEvent->scores as $score) {
                $attendances[$score->id]['event_date'] = Carbon::createFromFormat('Y-m-d', $calendarEvent->event_date)->format('D jS F Y');
                $attendances[$score->id]['discipline_label'] = $calendarEvent->discipline->label;
                $attendances[$score->id]['score'] = $score->getFormattedScore($calendarEvent->score_type);
            }
        }

        return [
            "data" => array_values($attendances),
            "pagination" => [],
        ];
    }

    /**
     * Print attendances pdf
     *
     * @return void
     **/
    public function printAttendances()
    {
        $attendances = $this->getAttendances(auth()->guard('member')->user()->id);

        Browsershot::createAttendancePdf($attendances['data']);
    }

    /**
     * Download attendances pdf
     *
     * @return file
     **/
    public function downloadAttendances()
    {
        return response()->file(
            storage_path('app/downloads/front-attendance/attendances-'.auth()->guard('member')->user()->id.'.pdf')
        );
    }
}
