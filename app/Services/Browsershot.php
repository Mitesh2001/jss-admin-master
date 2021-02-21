<?php

namespace App\Services;

use Spatie\Browsershot\Browsershot as BaseBrowsershot;

class Browsershot
{
    /**
     * Creates the PDF of the receipt.
     *
     * @param \App\Receipt
     * @return \Illuminate\Http\Response
     */
    public static function createReceipt($receipt)
    {
        $html = view('admin.receipts.print', compact('receipt'))->render();

        $pdfPath = storage_path('app/downloads/receipts/' . $receipt->id . '.pdf');

        BaseBrowsershot::html($html)->waitUntilNetworkIdle()->savePdf($pdfPath);

        return response()->file($pdfPath);
    }

    /**
     * Creates and returns the PDF of the payments report.
     *
     * @param array view parameters
     * @return \Illuminate\Http\Response
     */
    public static function createPaymentsReport($parameters)
    {
        $html = view('admin.reports.payments.print', $parameters)->render();

        return static::preparePdfReport($html);
    }

    /**
     * Creates and returns the PDF of the members report.
     *
     * @param array view parameters
     * @return \Illuminate\Http\Response
     */
    public static function createMembersReport($parameters)
    {
        $html = view('admin.reports.members.print', $parameters)->render();

        return static::preparePdfReport($html);
    }

    /**
     * Creates and returns the PDF of the WWC cards report.
     *
     * @param \Illuminate\Support\Collection $individuals
     * @return \Illuminate\Http\Response
     */
    public static function createWwcCardsReport($individuals)
    {
        $html = view('admin.reports.wwc_cards.print', compact('individuals'))->render();

        return static::preparePdfReport($html);
    }

    /**
     * Prepares and returns the PDF of the report.
     *
     * @param string html
     * @return \Illuminate\Http\Response
     */
    private static function preparePdfReport($html)
    {
        $directory = storage_path('app/downloads/temporary');
        static::existenceCheck($directory);
        $pdfPath = $directory . 'report.pdf';

        BaseBrowsershot::html($html)
            ->landscape()
            ->waitUntilNetworkIdle()
            ->margins(15, 0, 15, 0)
            ->savePdf($pdfPath)
        ;

        return response()->file($pdfPath);
    }

    /**
     * Prepares and returns the PDF of the attendances.
     *
     * @param string html
     * @return \Illuminate\Http\Response
     */
    public static function createAttendancePdf($attendances)
    {
        $html = view('front.auth.print_attandances', compact('attendances'))->render();

        $pdfPath = storage_path('app/downloads/front-attendance/attendances-'.auth()->guard('member')->user()->id.'.pdf');

        return BaseBrowsershot::html($html)->waitUntilNetworkIdle()->savePdf($pdfPath);
    }

    /**
     * Creates the directory is it doesn't exist.
     *
     * @param string Name of the directory
     * @return void
     */
    private static function existenceCheck($directory)
    {
        if (file_exists($directory)) {
            return;
        }

        mkdir($directory, $mode = '0755');
    }
}
