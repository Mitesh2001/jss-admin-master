<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MembersCsvReport implements FromCollection, WithHeadings
{
    /** @var \Illuminate\Support\Collection $members */
    private $members;

    public function __construct($members)
    {
        $this->members = $members;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->members->map(function ($member) {
            return [
                'membership_number' => $member->getMembershipNumber(),
                'name' => $member->getName(),
                'phone' => $member->mobile_number ?: $member->phone_number,
                'email' => $member->email_address,
                'status' => $member->membership->status ? 'Active' : 'Inactive',
                'member_type' => $member->member_type,
                'expiry' => $member->expiry_date,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Membership Number',
            'Name',
            'Phone',
            'Email',
            'Status',
            'Member Type',
            'Expiry',
        ];
    }
}
