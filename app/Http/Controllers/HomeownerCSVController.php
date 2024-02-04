<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Rules\HomeownerCsvStructure;
use App\Models\Files\CSV\HomeownerCSV;
use Illuminate\Support\Facades\Storage;

class HomeownerCSVController extends Controller
{
    public function csvUpload(Request $request)
    {
        try {
            $file = $request->file('file');

            $request->validate(
                [
                    'file' => ['required', 'mimes:csv', 'max:2048', new HomeownerCsvStructure]
                ],
                [
                    'file.required' => 'Please select a file first!',
                    'file.max' => 'The max file size is 2MB!',
                    'file.mimes' => 'The file must be a CSV file type!',
                ]
            );

            /** @todo In the future we could create a loop to save many files
             * and fire off a Job or Event should we want additional chaining of things
             * from a CSV upload event. Both would allow us to make use of queues should the wish to
             * persist the data to a DB be needed. Use chunking and transactions, and another
             * listeners for other logic flows.
            */
            // Saving the file to storage OTT for this but ready for larger files
            $tmpFile = Storage::putFile('files/csv-uploads', $file);

            if ($tmpFile === false) {
                throw new Exception('CSV file could not be saved to disk', 500);
            }

            $contents = Storage::get($tmpFile);

            $formattedArray = (new HomeownerCSV())->formatCsvToPersonDetailsArray($contents);

            // Deleting the temp file from storage as no longer required
            if (Storage::delete($tmpFile) === false) {
                throw new Exception('File failed to be deleted from disk', 500);
            }

            $headers = Arr::map(HomeownerCSV::PERSON_DETAILS_HEADER, function (string $value) {
                return Str::replace('_', '', $value);
            });

            // Lets remove the first index as it's the header row
            array_shift($formattedArray);

            return view('report-uploaded')->with(['headers' => $headers, 'homeowners' => $formattedArray]);
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
