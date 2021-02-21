<?php

namespace App\Http\Controllers\Admin;

use App\Discipline;
use App\Http\Controllers\Controller;
use App\ReceiptItem;
use App\Services\Browsershot;
use Carbon\Carbon;
use Freshbitsweb\Laratables\Laratables;

class ReportController extends Controller
{
    /**
     * Display Payment Details
     *
     * @return \Illuminate\Http\Response
     **/
    public function paymentReport()
    {
        $this->setFiltersInSessionIfRequired();

        $startDate = session('payments_start_date_filter');
        $endDate = session('payments_end_date_filter');

        $disciplines = Discipline::getList();

        return view('admin.reports.payments.index', compact('startDate', 'endDate', 'disciplines'));
    }

    /**
     * Returns the data for datatables.
     *
     * @return json
     */
    public function datatables()
    {
        return Laratables::recordsOf(ReceiptItem::class);
    }

    /**
     * Sets the session values for the payments data, if not set already.
     *
     * @return void
     */
    public function setFiltersInSessionIfRequired()
    {
        if (session()->exists('payment_discipline_type')) {
            return;
        }

        session(['payment_discipline_type' => 'all']);
        session(['payments_start_date_filter' => today()->subMonth()->startOfMonth()->format('Y-m-d')]);
        session(['payments_end_date_filter' => today()->subMonth()->endOfMonth()->format('Y-m-d')]);
    }

    /**
     * Sets the filter for the payments data.
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function filter()
    {
        session(['payments_start_date_filter' => request('start_date')]);
        session(['payments_end_date_filter' => request('end_date')]);
        session(['payment_discipline_type' => request('type')]);

        return back();
    }

    /**
     * Print report details
     *
     * @param int $type type of the report
     * @param string $startDate payment start date
     * @param string $endDate payment end date
     * @return \Illuminate\Http\Response
     **/
    public function print($type, $startDate, $endDate)
    {
        $filters = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'type' => $type != 0 ? $type : null,
        ];

        $receiptItems = ReceiptItem::applyFilters(
            $query = new ReceiptItem,
            $filters
        )->get();

        $receiptItems = $receiptItems->map(function ($receiptItem) {
            $receiptItem->paid_at = (new Carbon($receiptItem->receipt->payments->max('paid_at')))->format('Y-m-d');

            return $receiptItem;
        })->sortByDesc(function ($receiptItem) {
            return $receiptItem->receipt->payments->max('paid_at');
        });

        $startDate = (new Carbon($startDate))->format('d-M-y');
        $endDate = (new Carbon($endDate))->format('d-M-y');

        $reportType = $type == 0 ? 'Club Memberships / Other' : Discipline::find($type)->label;

        return Browsershot::createPaymentsReport(compact('receiptItems', 'startDate', 'endDate', 'reportType'));
    }
}
