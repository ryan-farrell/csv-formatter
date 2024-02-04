<?php

namespace App\Models\Files\CSV;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\Files\CSV\CSVFile;

class HomeownerCSV extends CSVFile
{
    const PERSON_DETAILS_HEADER = ['title', 'first_name', 'initial', 'last_name'];

    const PERSON_TITLES = [
        'Mr',
        'Mrs',
        'Miss',
        'Ms',
        'Mister',
        'Master',
        'Dr',
        'Doctor',
        'Doc',
        'Sir',
        'Dame',
        'Lord',
        'Lady',
        'Reverend',
        'Rev',
        'Revd',
        'Professor',
        'Prof',
        'Captain',
        'Count',
        'Viscount',
        'Baron',
        'Baroness',
        'Earl',
        'Countess',
        'Duchess',
        'Duke',
        'Prince',
        'Princess',
    ];

    /**
     * Counts the number of accepted titles in a homeowner string
     * see self::PERSON_TITLES for accepted titles
     *
     * @param string $str
     * @return int
     */
    public function countTitlesInHomeownerString(string $str): int
    {
        $titles = self::PERSON_TITLES;
        $titleCount = 0;

        // Split the homeowner string into an array of words
        $words = $this->splitStringIntoWords($str);

        // Iterate over the array of words
        foreach ($words as $word) {
            // If the word is a title, increment the title count
            if (in_array($word, $titles)) {
                $titleCount++;
            }
        }

        // Return the count of titles found
        return $titleCount;
    }

    /**
     * Splits a string into an array of words at each whitespace character
     *
     * @param string $str
     * @return array
     */
    public function splitStringIntoWords(string $str): array
    {
        // Split the string into an array of words
        return preg_split('/\s+/', $str);
    }

    /**
     * Checks if an array of words contains consecutive back-to-back titles
     *
     * @param array $words
     * @return bool
     */
    public function doesArrayContainBack2BackTitles(array $words): bool
    {
        // Iterate over the array of words
        for ($i = 0; $i < count($words) - 1; $i++) {
            // If the current word and the next word are both titles, return true
            if (in_array($words[$i], self::PERSON_TITLES) && in_array($words[$i + 1], self::PERSON_TITLES)) {
                return true;
            }
        }

        // If no consecutive back-to-back titles are found, return false
        return false;
    }

    /**
     * Get all titles from the homeowner string
     *
     * @param string $string
     * @return array
     */
    public function getTitlesFromHomeownerString(string $string): array
    {
        $titles = self::PERSON_TITLES;
        $titleArray = [];

        // Split the homeowner string into an array of words
        $words = $this->splitStringIntoWords($string);

        // Iterate over the array of words
        foreach ($words as $word) {
            // If the word is a title, increment the title count
            if (in_array($word, $titles)) {
                $titleArray[] = $word;
            }
        }

        // Return the count of titles found
        return $titleArray;
    }

    /**
     * This method will take a string of multiple person details and format it into an array
     * with each individual person details formatted.
     *
     * @param string $string The homeowner CSV string to format
     * @return array $multiplePersonsArray An array of person details nicely formatted
     **/
    public function formatMultiplePersonHomeowner(string $string): array
    {
        $multiplePersonsArray = [];
        $array = $this->splitStringIntoWords($string);

        if ($this->doesArrayContainBack2BackTitles($array)) {
            $individuals = $this->splitHomeownerStringAtSecondTitleWhenBack2Back($string);
        } else {
            $individuals = $this->splitHomeownerStringAtSecondTitleWhenNotBack2Back($string);
        };

        foreach ($individuals as $individual) {
            array_push($multiplePersonsArray, $this->formatPersonDetails($individual));
        }

        return $multiplePersonsArray;
    }

    /**
     * Splits a homeowner string with back-to-back titles into an array of strings
     * with complete person details. Essentially, it removes one title at a time
     *
     * @param string $str
     * @return array
     */
    public function splitHomeownerStringAtSecondTitleWhenBack2Back(string $str): array
    {
        // Split the homeowner string into an array of words
        $titles = $this->getTitlesFromHomeownerString($str);
        $persons = [];

        // For each title in the homeowner string remove from the string and push to a new array
        foreach ($titles as $title) {
            $person = preg_replace('/\b' . preg_quote($title, '/') . '\b/', '', $str);
            $person = Str::of($person)->squish()->value();
            $persons[] = $person;
        }

        return $persons;
    }

    /**
     * Splits a homeowner string into an array of strings at the second title
     * when titles are not back-to-back
     *
     * @param string $str
     * @return array
     */
    public function splitHomeownerStringAtSecondTitleWhenNotBack2Back(string $str): array
    {
        // Split the homeowner string into an array of words
        $titles = self::PERSON_TITLES;
        $words = $this->splitStringIntoWords($str);

        $words = collect(explode(' ', $str));

        $personDetails = collect();
        $currentSubstring = collect();

        // Lets check that our broken up strings each word is a title
        $words->each(function ($word) use ($titles, $personDetails, &$currentSubstring) {
            // We build the substring until we find the next title
            if (in_array($word, $titles)) {
                if (!$currentSubstring->isEmpty()) {
                    $personDetails->push($currentSubstring->implode(' '));
                    $currentSubstring = collect();
                }
            }
            $currentSubstring->push($word);
        });

        if (!$currentSubstring->isEmpty()) {
            $personDetails->push($currentSubstring->implode(' '));
        }

        return $personDetails->toArray();
    }

    /**
     * This method will take a sanitised and CSV prepared string of a person details and format it into an array
     * of their details for better output into this system (Model DB / Array / New CSV).
     *
     * @param string $str The string to format
     * @return array $personDetailsArray An array of person details nicely formatted
     **/
    public function formatPersonDetails(string $str): array
    {
        $personDetailsArray = [];
        $title = '';
        $firstname = '';
        $initial = '';
        $lastname = '';

        $arrayOfParts = Str::of($str)->explode(' ')->toArray();

        switch (count($arrayOfParts)) {
            case 4:
                $title = $arrayOfParts[0];
                $firstname = $arrayOfParts[1];
                $initial = $arrayOfParts[2];
                $lastname = $arrayOfParts[3];
                break;
            case 3:
                if (Str::length($arrayOfParts[1]) === 1) {
                    $title = $arrayOfParts[0];
                    $firstname = '';
                    $initial = $arrayOfParts[1];
                    $lastname = $arrayOfParts[2];
                } else {
                    $title = $arrayOfParts[0];
                    $firstname = $arrayOfParts[1];
                    $initial = Str::substr($arrayOfParts[1], 0, 1);
                    $lastname = $arrayOfParts[2];
                }
                break;
            case 2:
                $title = $arrayOfParts[0];
                $firstname = '';
                $initial = '';
                $lastname = $arrayOfParts[1];
                break;
            default:
                return $personDetailsArray;
                break;
        }

        $personDetailsArray = [
            Str::title($title),
            Str::title($firstname),
            Str::title($initial),
            Str::title($lastname),
        ];

        return $personDetailsArray;
    }

    /**
     * This method will take the contents of a Homeowner CSV file and format it
     * into an array of person details.
     *
     * @param string $contents The raw contents of a Person details CSV file
     * @return array $personDetailsArray An array of person details nicely formatted
     *
     * @throws Exception
     **/
    public function formatCsvToPersonDetailsArray(?string $csvFileContents): array
    {
        if (empty($csvFileContents)) {
            throw new Exception('CSV file contents are empty', 500);
        }

        $formattedArray = [];
        // Inserting the header row to the array
        $formattedArray = Arr::prepend($formattedArray, HomeownerCSV::PERSON_DETAILS_HEADER);

        $fileContentsArray = array_map('trim', str_getcsv($csvFileContents));

        foreach ($fileContentsArray as $key => $value) {
            if ($value === 'homeowner' || empty($value)) {
                Arr::pull($fileContentsArray, $key);
                continue;
            }

            // Clean the string of any unwanted characters
            $str = preg_replace('/(\b(?:and|AND|And)\b)|&|\./', '', $value);

            // Now we need to split the string in value name into title, firstname, initial, lastname
            $str = Str::of($str)->squish()->value();

            if ($this->countTitlesInHomeownerString($str) === 1) {
                // We are dealing with a single person, format the string
                $person = $this->formatPersonDetails($str);
                array_push($formattedArray, $person);
            } else {
                // We have more than one person in the string
                $persons = $this->formatMultiplePersonHomeowner($str);
                foreach ($persons as $person) {
                    array_push($formattedArray, $person);
                }
            }
        }

        return $formattedArray;
    }
}
