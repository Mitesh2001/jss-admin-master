<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Individual;
use App\Laratables\WwcCardReport;
use App\Services\Browsershot;
use Carbon\Carbon;
use Freshbitsweb\Laratables\Laratables;

class WwcCardsReportController extends Controller
{
    /**
     * Display WWC cards report
     *
     * @return \Illuminate\Http\Response
     **/
    public function index()
    {
        return view('admin.reports.wwc_cards.index');
    }

    /**
     * Returns the data for datatables.
     *
     * @return json
     */
    public function datatables()
    {
        return Laratables::recordsOf(Individual::class, WwcCardReport::class);
    }

    /**
     * Returns the pdf report for wwc cards
     *
     * @return \Illuminate\Http\File
     **/
    public function print()
    {
        $individuals = Individual::whereNotNull('wwc_card_number')
            ->whereNotNull('wwc_expiry_date')
            ->whereHas('membership', function ($query) {
                $query->where('status', 1);
            })
            ->orderBy('wwc_expiry_date', 'asc')
            ->get()
        ;

        $individuals->transform(function ($individual) {
            $individual->status = '<span class="text-danger">Expired</span>';

            $date = Carbon::createFromFormat('Y-m-d', $individual->wwc_expiry_date);
            if ($date->gt(now())) {
                $textColor = 'success';

                if ($date->lt(now()->addDays(10))) {
                    $textColor = 'warning';
                }

                $individual->status = '<span class="text-'.$textColor.'">'.$date->diffInDays(now()).' Days</span>';
            }

            $individual->status = $individual->wwc_expiry_date.' - '.$individual->status;

            return $individual;
        });

        return Browsershot::createWwcCardsReport($individuals);
    }
}
