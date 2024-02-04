<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Files\CSV\HomeownerCSV;
use Illuminate\Support\Facades\Storage;

class HomeownerCSVToFormattedCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:homeowner-csv-formatted';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert homeowner CSV to formatted CSV.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $contents = Storage::get('examples-284-29-1-.csv');

        $formattedArray = (new HomeownerCSV())->formatCsvToPersonDetailsArray($contents);

        // Create a CSV file from the formatted array
        $formattedCsv = implode("\r\n", array_map(function ($row) {
            return implode(',', $row);
        }, $formattedArray));

        $now = now()->toISOString();
        $storageDir = 'formatted-csv/';
        $fileTitle = '.csv';
        $formattedCsvFileName = $storageDir . 'homeowner-csv-new-frmt.'. $now . $fileTitle;

        Storage::put($formattedCsvFileName, $formattedCsv);
    }
}
