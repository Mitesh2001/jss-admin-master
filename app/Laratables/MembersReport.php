<?php

namespace App\Laratables;

use Carbon\Carbon;

class MembersReport
{
    /**
     * Returns the custom individual name column for datatables.
     *
     * @param \App\Individual
     * @return string
     */
    public static function laratablesCustomName($individual)
    {
        return view('admin.reports.members.includes.index_name', compact('individual'))->render();
    }

    /**
     * Returns the custom individual member type column for datatables.
     *
     * @param \App\Individual
     * @return string
     */
    public static function laratablesCustomMemberType($individual)
    {
        if ($individual->pension_card) {
            return 'Pensioner';
        }

        if ($individual->date_of_birth >= now()->startOfYear()->subYears(17)) {
            return 'Junier';
        }

        return 'Adult';
    }

    /**
     * Returns the name of the table column to be used for sorting when name column is selected.
     *
     * @return string
     */
    public static function laratablesOrderName()
    {
        return 'first_name';
    }

    /**
     * Returns formatted membership expiry date for the datatables.
     *
     * @param \App\Individual $individual
     * @return string
     */
    public static function laratablesMembershipExpiry($individual)
    {
        $expiryDateColor = '';
        $expiryDate = Carbon::createFromFormat('Y-m-d', $individual->membership->expiry);
        if ($expiryDate->gt(now()) && $expiryDate->year == now()->year) {
            $expiryDateColor = 'text-warning';
        }

        if ($expiryDate->lt(now())) {
            $expiryDateColor = 'text-danger';
        }

        $expiryDate = Carbon::createFromFormat('Y-m-d', $individual->membership->expiry)->format('d-m-Y');

        return '<strong class="'.$expiryDateColor.'">'.$expiryDate.'</strong>';
    }

    /**
     * Returns formatted membership status for the datatables.
     *
     * @param \App\Individual $individual
     * @return string
     */
    public static function laratablesMembershipStatus($individual)
    {
        $status = $individual->membership->status ? 'Active' : 'Inactive';
        $statusColor = $individual->membership->status ? 'success' : 'danger';

        return '<span class="badge badge-'.$statusColor.'">'.$status.'</span>';
    }

    /**
     * Adds the condition for searching the name of the individual in the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @param string search term
     * @param \Illuminate\Database\Eloquent\Builder
     */
    public static function laratablesSearchName($query, $searchValue)
    {
        return $query->orWhere('first_name', 'like', $searchValue)
            ->orWhere('surname', 'like', $searchValue)
            ->orWhereRaw('CONCAT(first_name, " ", surname) like ' . "'%" . $searchValue . "%'")
        ;
    }

    /**
     * Returns individual's mobile/phone number for datatables.
     *
     * @param \App\Individual
     * @return string
     */
    public static function laratablesCustomMobilePhoneNumber($individual)
    {
        return $individual->mobile_number ?: $individual->phone_number;
    }

    /**
     * Adds the condition for searching the mobile/phone number of the individual in the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @param string search term
     * @param \Illuminate\Database\Eloquent\Builder
     */
    public static function laratablesSearchMobilePhoneNumber($query, $searchValue)
    {
        return $query->orWhere('phone_number', 'like', $searchValue)
            ->orWhere('mobile_number', 'like', $searchValue)
        ;
    }

    /**
     * Additional columns to be loaded for datatables.
     *
     * @return array
     */
    public static function laratablesAdditionalColumns()
    {
        return ['first_name', 'surname', 'phone_number', 'mobile_number', 'date_of_birth', 'pension_card'];
    }

    /**
     * Specify filter conditions for the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @param \Illuminate\Database\Eloquent\Builder
     */
    public static function laratablesQueryConditions($query)
    {
        return static::applyFilters($query);
    }

    /**
     * Apply datatable filters
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @param array Filter values (optional)
     * @return \Illuminate\Database\Eloquent\Builder
     **/
    public static function applyFilters($query, $filters = null)
    {
        $filters = $filters ?: [
            'discipline' => session('members_discipline_type'),
            'expiration_status' => session('members_expiration_status'),
            'membership_status' => session('membership_status'),
            'member_types' => session('member_types'),
            'lifetime_status' => session('lifetime_status'),
            'is_next_year' => session('is_next_year'),
        ];

        if ($filters['discipline']) {
            $query = $query->whereHas('disciplines', function ($query) use ($filters) {
                $query->where('disciplines.id', $filters['discipline'])
                    ->whereNotNull('discipline_individual.registered_at')
                ;
            });
        }

        if ($filters['expiration_status']) {
            $query = $query->when($filters['expiration_status'] == 1, function ($query) {
                $query->whereHas('membership', function ($query) {
                    $query->whereDate('expiry', '<=', Carbon::now());
                });
            })->when($filters['expiration_status'] == 2, function ($query) {
                $query->whereHas('membership', function ($query) {
                    $query->whereDate('expiry', '>=', Carbon::now());
                });
            })->when($filters['expiration_status'] == 3, function ($query) {
                $query->whereHas('membership', function ($query) {
                    $query->whereYear('expiry', Carbon::now()->year);
                });
            })->when($filters['expiration_status'] == 4, function ($query) {
                $query->whereHas('membership', function ($query) {
                    $query->whereYear('expiry', Carbon::now()->subYear()->year);
                });
            })->when($filters['expiration_status'] == 5, function ($query) {
                $query->whereHas('membership', function ($query) {
                    $query->whereYear('expiry', Carbon::now()->addYear()->year);
                });
            })->when($filters['expiration_status'] == 6, function ($query) {
                $query->whereHas('membership', function ($query) {
                    $query->whereYear('expiry', '>=', Carbon::now()->addYear()->year);
                });
            });
        }

        if ($filters['membership_status']) {
            $query = $query->when($filters['membership_status'] == 1, function ($query) {
                $query->whereHas('membership', function ($query) {
                    $query->where('status', true);
                });
            }, function ($query) {
                $query->whereHas('membership', function ($query) {
                    $query->where('status', false);
                });
            });
        }

        if ($filters['lifetime_status']) {
            $query = $query->when($filters['lifetime_status'], function ($query) use ($filters) {
                $query->whereHas('disciplines', function ($query) use ($filters) {
                    $query->whereIn('discipline_id', $filters['lifetime_status'])
                        ->where('is_lifetime_member', true)
                    ;
                });

                if (in_array('lifetime_club', $filters['lifetime_status'])) {
                    $query->orWhere('is_club_lifetime_member', true);
                }
            });
        }

        if (
            $filters['member_types'] &&
            (! in_array(1, $filters['member_types']) ||
            ! in_array(2, $filters['member_types']) ||
            ! in_array(3, $filters['member_types']))
        ) {
            $query = static::getMemberTypeFilter($query, $filters);
        }

        return $query;
    }

    public static function getMemberTypeFilter($query, $filters)
    {
        $date = now()->startOfYear()->subYears(17)->format('Y-m-d');

        if ($filters['is_next_year']) {
            $date = now()->addYear()->startOfYear()->subYears(17)->format('Y-m-d');
        }

        if (in_array(1, $filters['member_types']) && in_array(2, $filters['member_types'])) {
            return $query->where('pension_card', false);
        }

        if (in_array(1, $filters['member_types']) && in_array(3, $filters['member_types'])) {
            return $query->where(function ($query) use ($date) {
                $query->whereDate('date_of_birth', '<=', $date)
                    ->where('pension_card', false)
                ;
            })->orWhere('pension_card', true);
        }

        if (in_array(2, $filters['member_types']) && in_array(3, $filters['member_types'])) {
            return $query->where(function ($query) use ($date) {
                $query->whereDate('date_of_birth', '>=', $date)
                    ->where('pension_card', false)
                ;
            })->orWhere('pension_card', true);
        }

        if (in_array(1, $filters['member_types'])) {
            return $query->whereDate('date_of_birth', '<=', $date)
                ->where('pension_card', false)
            ;
        }

        if (in_array(2, $filters['member_types'])) {
            return $query->whereDate('date_of_birth', '>=', $date)
                ->where('pension_card', false)
            ;
        }

        if (in_array(3, $filters['member_types'])) {
            return $query->where('pension_card', true);
        }
    }
}
