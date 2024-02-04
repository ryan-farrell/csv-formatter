<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class HomeownerCsvStructure implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $path = $value->getRealPath();
        $file = fopen($path, 'r');
        $csv = fgetcsv($file);

        // Check if the file is empty
        if ($csv === false) {
            $fail('The CSV file is empty, please check your file and try again.');
            return;
        }

        $headerToCheck = 'homeowner';

        // Is the first column header 'homeowner'?
        if ($csv[0] !== $headerToCheck) {
            $fail("The CSV file structure is invalid. Make sure the file is a \"{$headerToCheck}\" CSV. It should have the header \"{$headerToCheck}\" in the first column.");
            return;
        }

        /** @todo Further checks could be added here to check for other structures of the CSV file to ensure for this particular upload we have the correct attempted CSV*/
    }
}
