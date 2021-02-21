<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\IndividualRenewal;
use App\PaymentType;
use Freshbitsweb\Laratables\Laratables;

class IndividualRenewalController extends Controller
{
    /**
     * Display offline renewals.
     *
     * @return \Illuminate\Http\Response
     **/
    public function index()
    {
        $paymentTypes = PaymentType::getList();

        return view('admin.renewals.index', compact('paymentTypes'));
    }

    /**
     * Return list of offline renewals.
     *
     * @return json
     **/
    public function getIndividualRenewals()
    {
        return Laratables::recordsOf(IndividualRenewal::class);
    }

    /**
     * Sets the filter for records and displays the individual renewals.
     *
     * @return Illuminate\Http\Response
     */
    public function filter($filter = 'all')
    {
        session(['individual_renewals_filter' => $filter]);

        return $this->index();
    }

    /**
     * Sets the payment type filter for records and displays the individual renewals.
     *
     * @return Illuminate\Http\Response
     */
    public function paymentTypeFilter($filter = '')
    {
        session(['individual_renewals_payment_type_filter' => $filter]);

        return $this->index();
    }
}
