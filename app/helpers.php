<?php

use App\RenewalRun;

/**
 * Returns the month year of next year. Format - 'Jan 2019 - Dec 2019'
 *
 * @return string
 **/
function nextYearPeriod()
{
    $nextYear = now()->addYear()->format('Y');

    return "Jan " . $nextYear . " - Dec " . $nextYear;
}

/**
 * Returns the due date for the next year renewal.
 *
 * @return string
 **/
function nextYearDueDate()
{
    return now()->endOfYear()->addMonths(3)->format('Y-m-d');
}

/**
 * Returns the Start date for the next year renewal.
 *
 * @return string
 **/
function nextYearStartDate()
{
    return now()->addYear()->startOfYear()->format('Y-m-d');
}

/**
 * Returns the renewal link based on individual id and renewal run id.
 *
 * @param int Id of the individual
 * @param int Id of the renewal run
 * @return string
 **/
function getRenewalLink($individualId, $renewalRunId = null)
{
    if (! $renewalRunId) {
        $renewalRunId = RenewalRun::where('status', true)->value('id');
    }

    return url()->temporarySignedRoute(
        'front.individual_renewal',
        now()->addMonths(3),
        [
            'individual' => $individualId,
            'renewalRun' => $renewalRunId,
        ]
    );
}

/**
 * Returns the rounded amount with 2 decimals.
 *
 * @return string
 **/
function formattedRound($amount)
{
    return number_format(round($amount, 2), 2, '.', '');
}
