<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ExportPrintRunCsvReport;
use App\Http\Controllers\Controller;
use App\IdCard;
use App\Individual;
use App\Laratables\IdCardLaratables;
use App\PrintRunIdCard;
use Carbon\Carbon;
use Excel;
use Freshbitsweb\Laratables\Laratables;

class IdCardController extends Controller
{
    /**
     * Returns the list of the ID Cards
     *
     * @return \Illuminate\Http\Response
     **/
    public function index()
    {
        return view('admin.id_cards.index');
    }

    /**
     * Returns the data for datatables.
     *
     * @return json
     */
    public function datatables()
    {
        return Laratables::recordsOf(Individual::class, IdCardLaratables::class);
    }

    /**
     * Remove from print ID Card
     *
     * @return void
     **/
    public function removeFromPrintCard()
    {
        request()->validate([
            'id' => 'required|exists:id_cards,id',
            'individual_id' => 'required|exists:id_cards,individual_id'
        ]);

        IdCard::where('individual_id', request('individual_id'))
            ->where('id', request('id'))
            ->delete()
        ;
    }

    /**
     * Returns the list of the ID Cards
     *
     * @return \Illuminate\Http\Response
     **/
    public function printed()
    {
        return view('admin.id_cards.printed_index');
    }

    /**
     * Returns the data for datatables.
     *
     * @return json
     */
    public function printedDatatables()
    {
        return Laratables::recordsOf(IdCard::class);
    }

    /**
     * Send id cards to print run
     *
     * @return void
     **/
    public function sendToPrintRun()
    {
        request()->validate([
            'id_card_ids' => 'sometimes|array',
            'id_card_ids.*' => 'required|exists:id_cards,id'
        ]);

        $idCards = IdCard::query()
            ->with([
                'individual:id,first_name,surname',
                'individual.membership:id,individual_id,membership_number,join_date',
                'individual.membership.type:id,label',
                'individual.disciplines',
            ])
            ->whereNull('printed_at')
            ->when(request('id_card_ids'), function ($query) {
                $query->whereIn('id', request('id_card_ids'));
            })
            ->where('is_added_for_printrun', false)
            ->get()
        ;

        $cardDetails = collect([]);
        foreach ($idCards as $idCard) {
            $joinDate = optional(optional($idCard->individual)->membership)->join_date;

            if ($joinDate) {
                $joinDate = Carbon::createFromFormat('Y-m-d', $joinDate)->format('j F Y');
            }

            $cardDetails->push([
                'card_id' => $idCard->id,
                'full_name' => $idCard->individual->getName(),
                'membership_number' => optional($idCard->individual->membership)->membership_number,
                'member_since' => $joinDate ? 'Member since '.$joinDate : 'N/A',
                'discipline_list' => optional(optional(optional($idCard->individual)->disciplines)->pluck('label'))->implode(', '),
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        }

        // Send data to external database
        $printRunIdCard = new PrintRunIdCard();
        $printRunIdCard->setConnection('print_run');
        $printRunIdCard->insert($cardDetails->toArray());

        IdCard::query()
            ->when(request('id_card_ids'), function ($query) {
                $query->whereIn('id', request('id_card_ids'));
            })
            ->where('is_added_for_printrun', false)
            ->update(['is_added_for_printrun' => true])
        ;
    }

    /**
     * Send id cards to print run
     *
     * @return void
     **/
    public function removeFromPrintRun()
    {
        request()->validate([
            'id_card_ids' => 'sometimes|array',
            'id_card_ids.*' => 'required|exists:id_cards,id'
        ]);

        // Send data to external database
        $printRunIdCard = new PrintRunIdCard();
        $printRunIdCard->setConnection('print_run');
        $printRunIdCard->when(request('id_card_ids'), function ($query) {
            $query->whereIn('card_id', request('id_card_ids'));
        })->delete();

        IdCard::query()
            ->when(request('id_card_ids'), function ($query) {
                $query->whereIn('id', request('id_card_ids'));
            })
            ->where('is_added_for_printrun', true)
            ->whereNull('printed_at')
            ->update(['is_added_for_printrun' => false])
        ;

        return [
            'status' => 'success',
            'message' => 'Selected ID Cards removed from the Printrun successfully.'
        ];
    }

    /**
     * Send id cards to print run
     *
     * @return void
     **/
    public function markAsPrinted()
    {
        request()->validate([
            'id_card_ids' => 'sometimes|array',
            'id_card_ids.*' => 'required|exists:id_cards,id'
        ]);

        $printRunIdCard = new PrintRunIdCard();
        $printRunIdCard->setConnection('print_run');
        $printRunIdCard->when(request('id_card_ids'), function ($query) {
            $query->whereIn('card_id', request('id_card_ids'));
        })->delete();

        IdCard::query()
            ->when(request('id_card_ids'), function ($query) {
                $query->whereIn('id', request('id_card_ids'));
            })
            ->where('is_added_for_printrun', true)
            ->whereNull('printed_at')
            ->update(['printed_at' => now()->format('y-m-d H:i:s')])
        ;
    }

    /**
     * Clear print run entries
     *
     * @return void
     **/
    public function clearPrintRun()
    {
        $printRunIdCard = new PrintRunIdCard();
        $printRunIdCard->setConnection('print_run');
        $printRunIdCards = $printRunIdCard->get();
        $cardIds = $printRunIdCards->pluck('card_id')->toArray();

        if (empty($cardIds)) {
            return;
        }

        $updateDetails = ['is_added_for_printrun' => false];

        if (request('status') == 1) {
            $updateDetails = ['printed_at' => now()->format('y-m-d H:i:s')];
        }

        IdCard::query()
            ->whereIn('id', $cardIds)
            ->where('is_added_for_printrun', true)
            ->whereNull('printed_at')
            ->update($updateDetails)
        ;

        $printRunIdCard->whereIn('card_id', $cardIds)->delete();
    }

    /**
     * Sets the filter for records and displays the individuals.
     *
     * @return Illuminate\Http\Response
     */
    public function filter($filter = 'all')
    {
        session(['id_card_queue_filter' => $filter]);

        return $this->index();
    }

    /**
     * Export id cards to csv
     *
     * @return \Illuminate\Http\File
     **/
    public function exportToCsv()
    {
        $printRunIdCard = new PrintRunIdCard();
        $printRunIdCard->setConnection('print_run');
        $printRunIdCards = $printRunIdCard->get()->sortBy(function ($idCard) {
            $membershipNumber = explode(' ', $idCard->membership_number);

            return $membershipNumber[1];
        });

        return Excel::download(new ExportPrintRunCsvReport($printRunIdCards), 'id-cards.csv');
    }
}
