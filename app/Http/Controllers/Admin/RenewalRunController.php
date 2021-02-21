<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\RenewalRun;
use App\RenewalRunEntity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RenewalRunController extends Controller
{
    /**
     * Displays the renewal runs page.
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        $renewalRuns = RenewalRun::query()
            ->with(['emails' => function ($query) {
                $query->select('renewal_run_id', DB::raw('MAX(sent_at) as sent_at'))
                    ->whereNull('individual_id')
                    ->groupBy('renewal_run_id')
                ;
            }])
            ->withCount(['entities'])
            ->selectRaw(RenewalRun::renewalsSubmittedQuery())
            ->selectRaw(RenewalRun::renewalsProcessedQuery())
            ->get()
        ;

        $renewalRuns->transform(function ($renewalRun) {
            $submittedPercentage = $processedPercentage = 0;
            if ($renewalRun->entities_count > 0) {
                $submittedPercentage = ($renewalRun->submitted_count * 100) / $renewalRun->entities_count;
                $processedPercentage = ($renewalRun->processed_count * 100) / $renewalRun->entities_count;
            }

            $renewalRun->submitted_count .= ' (' . round($submittedPercentage, 2) . '%)';
            $renewalRun->processed_count .= ' (' . round($processedPercentage, 2) . '%)';

            return $renewalRun;
        });

        return view('admin.renewal_runs.index', compact('renewalRuns'));
    }

    /**
     * Displays the renewal run details page.
     *
     * @param int
     * @return Illuminate\Http\Response
     */
    public function details($renewalRunId)
    {
        $renewalRun = RenewalRun::withCount(['entities', 'emails' => function ($query) {
            $query->whereNull('individual_id');
        }])->selectRaw(RenewalRun::renewalsSubmittedQuery())->find($renewalRunId);

        $renewalRunEntities = RenewalRunEntity::getAllWith($renewalRun->id);

        $renewalRunEntities = $this->applyFilters($renewalRunEntities);

        $renewalRunEntities->transform(function ($renewalRunEntity) {
            $renewalRunEntity->status_label = RenewalRunEntity::decideStatus($renewalRunEntity);
            $renewalRunEntity->email_last_sent_at = $this->decideLastEmailSentAt($renewalRunEntity);

            return $renewalRunEntity;
        });

        return view('admin.renewal_runs.details', compact('renewalRun', 'renewalRunEntities'));
    }

    /**
     * Applies the filters to the renewal run entities data.
     *
     * @param \Illuminate\Support\Collection
     * @return \Illuminate\Support\Collection
     */
    private function applyFilters($renewalRunEntities)
    {
        return $renewalRunEntities->filter(function ($renewalRunEntity) {
            if (session('renewals_run_details_filter') == 'unsubmitted_only') {
                return ! $renewalRunEntity->renewal_row_id;
            }

            return true;
        });
    }

    /**
     * Decides and returns the last email sent at time for the entity.
     *
     * @param array
     * @return string
     */
    private function decideLastEmailSentAt($renewalRunEntity)
    {
        $a = $renewalRunEntity->renewal_run_email_sent_at;
        $b = $renewalRunEntity->single_renewal_run_email_sent_at;

        if ($a && $b) {
            $a = new Carbon($a);
            $b = new Carbon($b);

            return $a > $b ? $a : $b;
        }

        if (! $a && ! $b) {
            return 'N/A';
        }

        if (! $a) {
            return $b;
        }

        return $a;
    }

    /**
     * Sets the submitted filter for renewal run entities.
     *
     * @param int Id of the renewal run
     * @param string Submitted filter value
     * @return Illuminate\Http\Response
     */
    public function submittedFilter($renewalRunId, $filter = '')
    {
        session(['renewals_run_details_filter' => $filter]);

        return redirect()->route('admin.renewal_runs.details', ['renewalRun' => $renewalRunId]);
    }

    /**
     * Toggle renewal run status
     *
     * @param int $renewalRunId
     * @return \Illuminate\Http\RedirectResponse
     **/
    public function toggleStatus($renewalRunId)
    {
        RenewalRun::where('status', true)->update(['status' => false]);
        RenewalRun::where('id', $renewalRunId)->update(['status' => true]);

        return back()->with([
            'type' => 'success',
            'message' => 'Renewal run status updated successfully.'
        ]);
    }

    /**
     * Returns the create page for renewal run
     *
     * @return \Illuminate\Http\Response
     **/
    public function create()
    {
        return view('admin.renewal_runs.add');
    }

    /**
     * Saves the new renewal run
     *
     * @return \Illuminate\Http\RedirectResponse
     **/
    public function store()
    {
        $validatedData = request()->validate(RenewalRun::validationRules());

        $validatedData['period'] = 'Jan '.$validatedData['period'].' - Dec '.$validatedData['period'];

        RenewalRun::create($validatedData);

        return redirect()->route('admin.renewal-runs.index')->with([
            'type' => 'success',
            'message' => 'Renewal run created successfully.'
        ]);
    }

    /**
     * Edit specified renewal run
     *
     * @param \App\RenewalRun $renewalRun
     * @return \Illuminate\Http\Response
     **/
    public function edit(RenewalRun $renewalRun)
    {
        $renewalRun->period = substr($renewalRun->period, 4, 4);

        $currentYear = $renewalRun->period;

        return view('admin.renewal_runs.edit', compact('renewalRun', 'currentYear'));
    }

    /**
     * Updates the specified renewal run
     *
     * @param \App\RenewalRun $renewalRun
     * @return \Illuminate\Http\RedirectResponse
     **/
    public function update(RenewalRun $renewalRun)
    {
        $validatedData = request()->validate(RenewalRun::validationRules());

        $renewalRun->period = 'Jan '.$validatedData['period'].' - Dec '.$validatedData['period'];
        $renewalRun->payment_due_date = $validatedData['payment_due_date'];
        $renewalRun->start_date = $validatedData['start_date'];
        $renewalRun->expiry_date = $validatedData['expiry_date'];
        $renewalRun->save();

        return redirect()->route('admin.renewal-runs.index')->with([
            'type' => 'success',
            'message' => 'Renewal run updated successfully.'
        ]);
    }
}
