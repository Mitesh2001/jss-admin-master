<?php

namespace App\Http\Controllers\Admin;

use App\Discipline;
use App\Exports\MembersCsvReport;
use App\Http\Controllers\Controller;
use App\Individual;
use App\Laratables\MembersReport;
use App\Services\Browsershot;
use Carbon\Carbon;
use Freshbitsweb\Laratables\Laratables;
use Maatwebsite\Excel\Facades\Excel;

class MembersReportController extends Controller
{
    /**
     * Display Payment Details
     *
     * @return \Illuminate\Http\Response
     **/
    public function index()
    {
        $this->setFiltersInSessionIfRequired();

        $disciplines = Discipline::getList();

        return view('admin.reports.members.index', compact('disciplines'));
    }

    /**
     * Returns the data for datatables.
     *
     * @return json
     */
    public function datatables()
    {
        return Laratables::recordsOf(Individual::class, MembersReport::class);
    }

    /**
     * Sets the session values for the members data, if not set already.
     *
     * @return void
     */
    public function setFiltersInSessionIfRequired()
    {
        if (session()->exists('members_discipline_type')) {
            return;
        }

        session(['members_discipline_type' => 0]);
        session(['membership_status' => 1]);
        session(['members_expiration_status' => 1]);
    }

    /**
     * Sets the filter for the members data.
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function filter()
    {
        session(['members_discipline_type' => request('discipline')]);
        session(['members_expiration_status' => request('members_expiration_status')]);
        session(['membership_status' => request('membership_status')]);
        session(['member_types' => request('member_types')]);
        session(['lifetime_status' => request('lifetime_status')]);
        session(['is_next_year' => request('is_next_year')]);

        return back();
    }

    /**
     * Print report details
     *
     * @param int $disciplineId
     * @param bool $expirationStatus
     * @param bool $membershipStatus
     * @return \Illuminate\Http\Response
     **/
    public function print($disciplineId, $expirationStatus, $membershipStatus)
    {
        $filters = [
            'discipline' => $disciplineId,
            'expiration_status' => $expirationStatus,
            'membership_status' => $membershipStatus,
            'member_types' => request('member_types'),
            'lifetime_status' => request('lifetime_status'),
            'is_next_year' => request('is_next_year')
        ];

        $members = MembersReport::applyFilters(
            $query = new Individual,
            $filters
        )->get();

        $members->load('membership');

        $reportType = $disciplineId == 0 ? 'All Disciplines' : Discipline::find($disciplineId)->label;
        $expirationStatusLabel = $expirationStatus == 1 ? 'Expired' : 'Non-expired';
        $expirationStatusText = $expirationStatus ? '('.$expirationStatusLabel.' only)' : '';

        $membershipStatusLabel = $membershipStatus == 1 ? 'Active' : 'Inactive';
        $membershipStatusText = $membershipStatus ? '('.$membershipStatusLabel.' only)' : '';

        $members = $members->map(function ($member) {
            $member->member_type = MembersReport::laratablesCustomMemberType($member);
            $member->expiry_date = Carbon::createFromFormat('Y-m-d', $member->membership->expiry)->format('d-m-Y');

            return $member;
        });

        return Browsershot::createMembersReport(compact('members', 'reportType', 'expirationStatusText', 'membershipStatusText'));
    }

    /**
     * Returns the csv of the members report
     *
     * @param int $disciplineId
     * @param bool $expirationStatus
     * @param bool $membershipStatus
     * @return \Illuminate\Http\File
     **/
    public function exportCsv($disciplineId, $expirationStatus, $membershipStatus)
    {
        $filters = [
            'discipline' => $disciplineId,
            'expiration_status' => $expirationStatus,
            'membership_status' => $membershipStatus,
            'member_types' => request('member_types'),
            'lifetime_status' => request('lifetime_status'),
            'is_next_year' => request('is_next_year')
        ];

        $members = MembersReport::applyFilters(
            $query = new Individual,
            $filters
        )->get();

        $members = $members->map(function ($member) {
            $member->member_type = MembersReport::laratablesCustomMemberType($member);
            $member->expiry_date = Carbon::createFromFormat('Y-m-d', $member->membership->expiry)->format('d-m-Y');

            return $member;
        });

        return Excel::download(new MembersCsvReport($members), 'members-report.csv');
    }
}
