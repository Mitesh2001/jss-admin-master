<?php

use App\BranchCode;
use App\Discipline;
use App\EmailType;
use App\FirearmType;
use App\Gender;
use App\MembershipType;
use App\PaymentType;
use App\ReceiptItemCode;
use App\RenewalRun;
use App\SparkpostTemplate;
use App\State;
use App\Suburb;
use Illuminate\Database\Seeder;

class StaticDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Gender::create(['label' => 'Male']);
        Gender::create(['label' => 'Female']);

        State::create(['label' => 'ACT']);
        State::create(['label' => 'NT']);
        State::create(['label' => 'SA']);
        State::create(['label' => 'WA']);
        State::create(['label' => 'NSW']);
        State::create(['label' => 'VIC']);
        State::create(['label' => 'QLD']);
        State::create(['label' => 'TAS']);

        BranchCode::create(['label' => 'W01']);
        BranchCode::create(['label' => 'W02']);
        BranchCode::create(['label' => 'W03']);
        BranchCode::create(['label' => 'W04']);
        BranchCode::create(['label' => 'W05']);
        BranchCode::create(['label' => 'W06']);
        BranchCode::create(['label' => 'W07']);
        BranchCode::create(['label' => 'W08']);
        BranchCode::create(['label' => 'W09']);
        BranchCode::create(['label' => 'W10']);

        Discipline::create([
            'label' => 'Practical Shooting',
            'adult_price' => 90,
            'family_price' => 110,
            'pensioner_price' => 90,
        ]);
        Discipline::create([
            'label' => 'Single Action',
            'adult_price' => 65,
            'family_price' => 105,
            'pensioner_price' => 65,
        ]);
        Discipline::create([
            'label' => 'Benchrest',
            'adult_price' => 100,
            'family_price' => 125,
            'pensioner_price' => 100,
        ]);
        Discipline::create([
            'label' => 'Field Pistol',
            'adult_price' => 85,
            'family_price' => 105,
            'pensioner_price' => 85,
        ]);
        Discipline::create([
            'label' => 'Field Archery',
            'adult_price' => 65,
            'family_price' => 105,
            'pensioner_price' => 65,
        ]);
        Discipline::create([
            'label' => 'Field Rifle',
            'adult_price' => 60,
            'family_price' => 80,
            'pensioner_price' => 60,
        ]);
        Discipline::create([
            'label' => 'Shotgun',
            'adult_price' => 0,
            'family_price' => 0,
            'pensioner_price' => 0,
        ]);
        Discipline::create([
            'label' => 'IHMS',
            'adult_price' => 0,
            'family_price' => 0,
            'pensioner_price' => 0,
        ]);

        MembershipType::create([
            'label' => 'Adult',
            'price' => 130
        ]);
        MembershipType::create([
            'label' => 'Family',
            'price' => 145
        ]);
        MembershipType::create([
            'label' => 'Pensioner',
            'price' => 100
        ]);

        $this->seedSuburbs();

        $this->seedEventTypes();

        PaymentType::create(['label' => 'Online Payment']);
        PaymentType::create(['label' => 'Bank Transfer']);
        PaymentType::create(['label' => 'Cash']);
        PaymentType::create(['label' => 'Cheque']);
        PaymentType::create(['label' => 'Money Order']);
        PaymentType::create(['label' => 'EFTPOS']);
        PaymentType::create(['label' => 'Other']);

        EmailType::create([
            'label' => 'Initial',
        ]);

        EmailType::create([
            'label' => 'Reminder',
        ]);

        SparkpostTemplate::create([
            'name' => 'JSS Renewal',
            'template_id' => 'jss-renewal',
            'email_type_id' => 1,
        ]);

        SparkpostTemplate::create([
            'name' => 'JSS Renewal Reminder',
            'template_id' => 'jss-renewal-reminder',
            'email_type_id' => 2,
        ]);

        SparkpostTemplate::create([
            'name' => 'JSS Renewal Confirmation',
            'template_id' => 'jss-renewal-confirmation',
        ]);

        SparkpostTemplate::create([
            'name' => 'JSS Member Portal Registration',
            'template_id' => 'jss-memberportal-registration',
        ]);

        RenewalRun::create([
            'period' => nextYearPeriod(),
            'payment_due_date' => nextYearDueDate(),
            'start_date' => nextYearStartDate(),
            'expiry_date' => nextYearDueDate(),
            'status' => true,
        ]);

        ReceiptItemCode::create(['label' => 'Adult']);
        ReceiptItemCode::create(['label' => 'Family']);
        ReceiptItemCode::create(['label' => 'Pensioner']);
        ReceiptItemCode::create([
            'label' => 'Other',
            'description' => 'Other',
            'amount' => 0,
        ]);

        $firearmTypes = [
            "Combination Rifle/Shotgun", "Combination Rifle", "Handgun Air or Gas", "Handgun Barrel Only", "Handgun Bolt Repeater", "Handgun Double Barrel", "Handgun Flintlock", "Handgun Multi Barrel", "Handgun Matchlock", "Handgun Percussion", "Handgun Percussion Revolver", "Handgun Repeating", "Handgun Revolver", "Handgun Self Loading", "Handgun Single Shot", "Handgun Wheel Lock", "Incendiary Launcher", "Captive Bolt Gun", "Cannon", "Flare Pistol", "Gas Launcher", "Paintball Gun", "Line Thrower", "Mortar", "Powerhead", "Pneum. Caps Launcher", "Target Launcher", "Tranquilliser", "Walking Stick Firearm", "Net Gun", "Fully Auto Firearm", "Bazooka", "Rifle Revolver Chamber", "Machine Gun", "Rifle Air or Gas", "Rifle Bolt Repeater", "Rifle Double Barrel", "Rifle Barrel Only", "Rifle Bolt Only", "Rifle Flintlock", "Rifle Lever Repeater", "Rifle Multi Barrel", "Rifle Matchlock", "Riot Gun", "Rifle Pump Repeater", "Rifle Percussion Barrel Only", "Rifle Percussion", "Rifle Percussion Multi Barrel", "Rifle Percussion Revolver Chamber", "Rifle Self Loading Centre Fire", "Rifle Self Loading Rim Fire", "Rifle Single Shot", "Rifle Wheel Lock", "Shotgun Bolt Repeater", "Shotgun Flintlock", "Shotgun Lever Repeater", "Shotgun Matchlock", "Shotgun Pump Repeater", "Shotgun Percussion", "Shotgun Single Shot", "Shotgun Double Barrel Side/Side", "Shotgun Self Loading", "Shotgun Double Barrel Under/Over", "Shotgun Wheel Lock"
        ];

        foreach ($firearmTypes as $firearmType) {
            FirearmType::create([
                'label' => $firearmType,
            ]);
        }
    }

    /**
     * Seeds the suburbs table.
     *
     * @return void
     */
    private function seedSuburbs()
    {
        if (config('app.env') == 'testing') {
            Suburb::create(['label' => 'Rajkot', 'state_id' => 1]);
            Suburb::create(['label' => 'Rajkot', 'state_id' => 3]);
            Suburb::create(['label' => 'Rajkot', 'state_id' => 2]);
            Suburb::create(['label' => 'Rajkot', 'state_id' => 4]);
            Suburb::create(['label' => 'Rajkot', 'state_id' => 5]);
            Suburb::create(['label' => 'Rajkot', 'state_id' => 6]);
            Suburb::create(['label' => 'Rajkot', 'state_id' => 7]);
            Suburb::create(['label' => 'Rajkot', 'state_id' => 8]);

            return;
        }

        $this->seedFromCsvFile(
            'suburbs.csv',
            $columns = ['label', 'state_id'],
            'App\Suburb'
        );
    }

    /**
     * Seeds the event types table.
     *
     * @return void
     */
    private function seedEventTypes()
    {
        $this->seedFromCsvFile(
            'event_types.csv',
            $columns = ['id', 'label'],
            'App\EventType'
        );
    }

    /**
     * Seeds the data from the csv file table.
     *
     * @param string CSV file name
     * @param array columns of the table
     * @param string Model name
     * @return void
     */
    private function seedFromCsvFile($csvFile, $columns, $model)
    {
        if (($handle = fopen(database_path() . '/seeds/' . $csvFile, 'r')) !== false) {
            $records = [];
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $fields = [];
                foreach ($columns as $key => $value) {
                    $fields[$value] = $data[$key];
                }

                $records[] = array_merge($fields, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Save memory by storing 1k records, if collected
                if (count($records) > 999) {
                    $model::insert($records);
                    $records = [];
                }
            }

            fclose($handle);

            if (count($records)) {
                $model::insert($records);
            }
        }
    }
}
