<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Files\CSV\HomeownerCSV;

class HomeownerCSVFormatterTest extends TestCase
{
    private const CSV_FILE_CONTENTS = "homeowner,\r\nMaster   Peter P   Parker,\r\n Mr. Tony Stark,\r\n Miss N    Romanoff,\r\n Mr & Mrs A Anderson,\r\n Mr Ryan Farrell and Miss K Anderson,\r\n Mr and Mrs Smith,\r\nMr Tom Jones and Mr John Doe\r\n Mr Banner,";

    private HomeownerCSV $csvFile;

    protected function setUp(): void
    {
        parent::setUp();
        $this->csvFile = new HomeownerCSV();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->csvFile);
    }

    public function test_count_titles_in_homeowner_string_and_returns_an_integer(): void
    {
        // By this stage the string will be cleaned of additional white spaces and special characters
        $strWithNoTitle = 'T Jones'; // Count 1
        $strWith1Title = 'Mr Jones'; // Count 1
        $strNotBack2Back = 'Mr Tom Jones Mrs John Doe'; // Count 2
        $strBack2BackSameTitle = 'Mr Mr Smith'; // Count 2

        $this->assertIsInt($this->csvFile->countTitlesInHomeownerString($strBack2BackSameTitle));

        $this->assertEquals(0, $this->csvFile->countTitlesInHomeownerString($strWithNoTitle));
        $this->assertEquals(1, $this->csvFile->countTitlesInHomeownerString($strWith1Title));
        $this->assertEquals(2, $this->csvFile->countTitlesInHomeownerString($strNotBack2Back));
        $this->assertEquals(2, $this->csvFile->countTitlesInHomeownerString($strBack2BackSameTitle));
        $this->assertEquals(2, $this->csvFile->countTitlesInHomeownerString($strBack2BackSameTitle));

        // More than one - Goes back to back method
        // Just one - goes to person formatter method
    }

    public function test_split_homeowner_string_into_individual_components_splits_correctly_and_returns_array(): void
    {
        // By this stage the string will be cleaned of additional white spaces and special characters
        $strWithNoTitle = 'T Jones'; // Count 2
        $strWith1Title = 'Mr Jones'; // Count 2
        $strNotBack2Back = 'Mr Tom Jones Mrs John Doe'; // Count 6
        $strBack2BackSameTitle = 'Mr Mr Smith'; // Count 3

        $this->assertIsArray($this->csvFile->splitStringIntoWords($strBack2BackSameTitle));

        $this->assertCount(2, $this->csvFile->splitStringIntoWords($strWithNoTitle));
        $this->assertCount(2, $this->csvFile->splitStringIntoWords($strWith1Title));
        $this->assertCount(6, $this->csvFile->splitStringIntoWords($strNotBack2Back));
        $this->assertCount(3, $this->csvFile->splitStringIntoWords($strBack2BackSameTitle));
    }

    public function test_array_for_consecutive_back_to_back_titles(): void
    {
        // By this point we will dealing with an array in the order the individual components
        // were created from the homeowner string
        $arrayWithNoTitle = [
            0 => 'T',
            1 => 'Jones'
        ]; // False


        $arrayWith1Title = [
            0 => 'Mr',
            1 => 'Jones'
        ]; // False

        $arrayNotBack2Back = [
            0 => 'Mr',
            1 => 'Tom',
            2 => 'Jones',
            3 => 'Mrs',
            4 => 'John',
            5 => 'Doe'
        ]; // False

        $arrayBack2BackSameTitle = [
            0 => 'Mr',
            1 => 'Mr',
            2 => 'Smith'
        ]; // True

        $arrayBack2BackDiffTitle = [
            0 => 'Dr',
            1 => 'Mr',
            2 => 'Smith'
        ]; // True


        $this->assertFalse($this->csvFile->doesArrayContainBack2BackTitles($arrayWithNoTitle));
        $this->assertFalse($this->csvFile->doesArrayContainBack2BackTitles($arrayWith1Title));
        $this->assertFalse($this->csvFile->doesArrayContainBack2BackTitles($arrayNotBack2Back));
        $this->assertTrue($this->csvFile->doesArrayContainBack2BackTitles($arrayBack2BackSameTitle));
        $this->assertTrue($this->csvFile->doesArrayContainBack2BackTitles($arrayBack2BackDiffTitle));
        // If true goes to parse back to back method
        // If false goes to parse non back to back splitter method method
    }

    public function test_homeowner_string_titles_separates_string_correctly_when_we_DO_have_back_to_back_titles(): void
    {
        // By this stage the string will be cleaned of additional white spaces and special characters
        $strNotBack2BackDiffTitle = 'Mr Mrs Smith';
        // We should have an array of 2 arrays each with strings.
        // Array 1 = 'Mr Smith'
        // Array 2 = 'Mrs Smith'
        // We can do this by removing a title each turn
        $this->assertIsArray($this->csvFile->splitHomeownerStringAtSecondTitleWhenBack2Back($strNotBack2BackDiffTitle));
        $this->assertCount(2, $this->csvFile->splitHomeownerStringAtSecondTitleWhenBack2Back($strNotBack2BackDiffTitle));
        $this->assertContains('Mrs Smith', $this->csvFile->splitHomeownerStringAtSecondTitleWhenBack2Back($strNotBack2BackDiffTitle));
        $this->assertContains('Mr Smith', $this->csvFile->splitHomeownerStringAtSecondTitleWhenBack2Back($strNotBack2BackDiffTitle));
    }

    public function test_homeowner_string_titles_separates_string_correctly_when_we_DONT_have_back_to_back_titles(): void
    {
        // By this stage the string will be cleaned of additional white spaces and special characters
        $strNotBack2Back = 'Mr Tom Jones Mrs John Doe'; //
        // We should have an array of 2 arrays each with strings.
        // Array 1 = 'Mr Tom Jones'
        // Array 2 = 'Mrs John Doe'
        // Find the next title and split the string
        $this->assertIsArray($this->csvFile->splitHomeownerStringAtSecondTitleWhenNotBack2Back($strNotBack2Back));
        $this->assertCount(2, $this->csvFile->splitHomeownerStringAtSecondTitleWhenNotBack2Back($strNotBack2Back));
        $this->assertEquals('Mr Tom Jones', $this->csvFile->splitHomeownerStringAtSecondTitleWhenNotBack2Back($strNotBack2Back)[0]);
        $this->assertEquals('Mrs John Doe', $this->csvFile->splitHomeownerStringAtSecondTitleWhenNotBack2Back($strNotBack2Back)[1]);
    }

    public function test_get_titles_from_homeowner_string_returns_all_known_titles(): void
    {
        // By this stage the string will be cleaned of additional white spaces and special characters
        $strWithNoTitle = 'Tom Jones'; // count 1
        $stringWithOneTitle = 'Mr Tom Jones'; // count 1
        $strWithTwoTitles = 'Mr Tom Jones Prof John Doe'; // count 2
        $strWithThreeTitles = 'Dr Tom Jones Mrs John Doe Master James Jones'; // count 3

        // We should have an array with X strings.
        $this->assertIsArray($this->csvFile->getTitlesFromHomeownerString($strWithNoTitle));
        $this->assertEmpty($this->csvFile->getTitlesFromHomeownerString($strWithNoTitle));
        $this->assertCount(0, $this->csvFile->getTitlesFromHomeownerString($strWithNoTitle));

        $this->assertIsArray($this->csvFile->getTitlesFromHomeownerString($stringWithOneTitle));
        $this->assertCount(1, $this->csvFile->getTitlesFromHomeownerString($stringWithOneTitle));

        $this->assertIsArray($this->csvFile->getTitlesFromHomeownerString($strWithTwoTitles));
        $this->assertCount(2, $this->csvFile->getTitlesFromHomeownerString($strWithTwoTitles));

        $this->assertIsArray($this->csvFile->getTitlesFromHomeownerString($strWithThreeTitles));
        $this->assertCount(3, $this->csvFile->getTitlesFromHomeownerString($strWithThreeTitles));
    }

    public function test_string_formats_person_details_ready_in_correct_format(): void
    {
        // By this stage the string will be cleaned of additional white spaces and special characters
        $strPrepReady1 = 'Mr John Doe';
        $expected1 = ['Mr', 'John', 'J', 'Doe'];
        $this->assertEquals($expected1, $this->csvFile->formatPersonDetails($strPrepReady1));

        $strPrepReady2 = 'Dr J Smith';
        $expected2 = ['Dr', '', 'J', 'Smith'];
        $this->assertEquals($expected2, $this->csvFile->formatPersonDetails($strPrepReady2));

        $strPrepReady3 = 'Mrs Johnson';
        $expected3 = ['Mrs', '', '', 'Johnson'];
        $this->assertEquals($expected3, $this->csvFile->formatPersonDetails($strPrepReady3));

        // Test case 4: Invalid input (empty string)
        $strPrepReady4 = '';
        $expected4 = [];
        $this->assertEquals($expected4, $this->csvFile->formatPersonDetails($strPrepReady4));
    }

    /**
     * Test that the formatCsvToPersonDetailsArray method returns an array of person details.
     */
    public function test_formatCsvToPersonDetailsArray_returns_array_of_person_details(): void
    {
        $personsDetailsArray = $this->csvFile->formatCsvToPersonDetailsArray(self::CSV_FILE_CONTENTS);

        $expectedArray = [
            ['title', 'first_name', 'initial', 'last_name'],
            ['Master', 'Peter', 'P', 'Parker'],
            ['Mr', 'Tony', 'T', 'Stark'],
            ['Miss', '', 'N', 'Romanoff'],
            ['Mrs', '', 'A', 'Anderson'],
            ['Mr', '', 'A', 'Anderson'],
            ['Mr', 'Ryan', 'R', 'Farrell'],
            ['Miss', '', 'K', 'Anderson'],
            ['Mrs', '', '', 'Smith'],
            ['Mr', '', '', 'Smith'],
            ['Mr', 'Tom', 'T', 'Jones'],
            ['Mr', 'John', 'J', 'Doe'],
            ['Mr', '', '', 'Banner'],
        ];

        $this->assertIsArray($personsDetailsArray);
        $this->assertContains(HomeownerCSV::PERSON_DETAILS_HEADER, $personsDetailsArray);
        $this->assertCount(13, $personsDetailsArray);
        $this->assertCount(4, $personsDetailsArray[1]);
        $this->assertEquals($expectedArray, $personsDetailsArray);
    }
}
