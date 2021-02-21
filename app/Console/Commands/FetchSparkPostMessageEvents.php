<?php

namespace App\Console\Commands;

use App\Services\Sparkpost;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FetchSparkPostMessageEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sparkpost:message_events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches message events from sparkpost.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($messageEvents = (new Sparkpost)->messageEvents()) {
            $results = collect($messageEvents['results']);

            if ($results->isNotEmpty()) {
                $records = $results->map(function ($item) {
                    $individualId = null;
                    if (isset($item['rcpt_meta']) && $item['rcpt_meta']) {
                        $individualId = $item['rcpt_meta']['IndividualId'] ?? null;
                    }

                    return implode('", "', [
                        $item['event_id'],
                        $item['type'],
                        $item['injection_time'] ?? null,
                        $individualId,
                        $item['rcpt_to'] ?? null,
                        $item['subject'] ?? null,
                        $item['template_id'] ?? null,
                        $item['transmission_id'] ?? null,
                        $item['timestamp'],
                        now(),
                        now(),
                    ]);
                })->implode('"), ("');

                $insertStatement = 'insert ignore into sparkpost_message_events (id, type, injection_time, individual_id, rcpt_to, subject, template_id, transmission_id, timestamp, created_at, updated_at) values ("' . $records . '")';

                DB::insert($insertStatement);
            }
        }
    }
}
