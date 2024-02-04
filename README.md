<p align="left">
<a href="https://github.com/ryan-farrell?tab=repositories">My Repos</a>
</p>
<hr>

# CSV Formatter

Technologies Used
Laravel 10 / Tailwind / Blade

### Headline Steps / Considerations
No DB or persistent data was required.

Created the phpunit test suite with TDD red-green-refactor approach.

Once I had green tests, I created a new CSV using the cmd `php artisan app:homeowner-csv-formatted` to save a newly formatted version of your CSV after the require as visual confirmation.

An example CSV is already saved in the `storage > app` folder and its this file that will get reformatted when the command is run. See dir `storage > app > formatted-csv` for output.
Created a new frontend view for the HomeownerCSVController to allow the user to upload a file and submit it to the server. The server will then process the file, reformat and return rendered array in a table as per requirements.

## Prerequisites
You will need to have the following installed on your machine:

- Laravel
- Composer
- NPM

## Installation

To install the project, please run the following commands in your terminal:

1. Clone the repo > `git clone https://github.com/ryan-farrell/csv-formatter.git`
2. cd into dir
3. Install Composer packages > `composer install`
4. Install NPM packages > `npm install`
5. Check Unit Tests > `./vendor/bin/phpunit`

Files I've added/amended from a new Laravel project download:

- `Console > Commands > HomeownerCSVToFormattedCSV.php`
- `Http > Controllers > HomeownerCSVController.php`
- `Models > File > CSVFile > HomeownerCSV.php`
- `Rules > HomeownerCsvStructure.php`
- `Tests > Unit > HomeownerCSVFormatterTest.php`
- `Resources > Views > 3 New Blade View Files`
- `Added new routes to web.php`

## Getting Started

To run the project, please run the following command in your terminal:

npm run build && npm run dev
php artisan serve
Navigate to http://localhost:8000 in your browser to view the project

## Test Scenarios So Far:

1. Attempted Empty file upload
2. Attempted file NOT CSV type
3. Attempted when file size is too large
4. Attempted when file NOT the Expected Homeowner CSV

## Considerations
There is a couple of `@todo`'s which you can search for in the codebase. These my thoughts and things to improve upon once further requirements can be confirmed.

## Future Improvements
! Catching errors with try catch to display message on faulty upload and formatting. Issue with displaying information to a the user they shouldn't see.