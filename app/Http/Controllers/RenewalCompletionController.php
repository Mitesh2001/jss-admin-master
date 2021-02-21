<?php

namespace App\Http\Controllers;

use App\Individual;

class RenewalCompletionController extends Controller
{
    /**
     * Displays the thank you page for individual renewal submission with online payment.
     *
     * @param int Id of the individual
     * @param int Id of the renewal run
     * @param bool Whether it's a family renewal
     * @param bool is family 2nd or 3rd member
     * @param string Transaction Id
     * @return \Illuminate\Http\Response
     */
    public function individualThankYou($individualId, $renewalRunId, $isFamily, $isFamilyRenewalAlreadyPaid, $transactionId = null)
    {
        $individual = Individual::with('membership')
            ->findOrFail($individualId)
        ;

        return view('front.renewals.individual.complete.thank_you', compact('individual', 'transactionId', 'isFamily', 'isFamilyRenewalAlreadyPaid'));
    }

    /**
     * Displays the page when an individual has already requested a renewal.
     *
     * @return \Illuminate\Http\Response
     */
    public function requestedAlready()
    {
        return view('front.renewals.common.requested_already');
    }
}
