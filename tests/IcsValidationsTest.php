<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

use HummingbirdSolutions\Phpics\Index;

final class IcsValidationsTest extends TestCase
{
    public function testThrowsValidationExceptions(): void
    {
        $a = new Index();

        $this->expectException(Exception::class);
        $a->generateIcs();
    }

    private function testIcsEqual($expect, $actual, $excludeArr) {
        $expectParts = explode( "\n", $expect );
        $actualParts = explode( "\n", $actual );

        $arrLen = count($expectParts);
        for ($x = 0; $x < $arrLen; $x++) {
            if (in_array($x, $excludeArr)) {
                continue;
            }

            $this->assertEquals(
                $expectParts[$x],
                $actualParts[$x],
                "actual value is not equals to expected"
            );
        }
    }

    public function testValidStartEndDate(): void
    {
        $ics = new Index();
        
        $expect = 'BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//Hummingbird Solutions//NONSGML PHP ICS//EN
CALSCALE:GREGORIAN
BEGIN:VEVENT
DTSTART;TZID=America/New_York:20220606T193013
DTEND;TZID=America/New_York:20220606T195013
UID:8e91754d-4603-40c7-9bc7-07819767ad81
DTSTAMP;TZID=America/New_York:20220525T170449
LOCATION:1600 Pennsylvania Avenue NW\, Washington\, DC 20500
DESCRIPTION:Meet with the President
SUMMARY:Very important meeting
END:VEVENT
END:VCALENDAR
';

        $actual = $ics->generateIcs("Very important meeting", "Meet with the President", "2022-06-06 19:30:13", "2022-06-06 19:50:13", "1600 Pennsylvania Avenue NW, Washington, DC 20500");
        // exclude UID and DTSTAMP
        $excludeArr = [7, 8];
        $this->testIcsEqual($expect, $actual, $excludeArr);
    }

    public function testValidFullDay(): void
    {
        $ics = new Index();
        
        $expect = 'BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//Hummingbird Solutions//NONSGML PHP ICS//EN
CALSCALE:GREGORIAN
BEGIN:VEVENT
DTSTART;TZID=America/New_York:20100206
UID:292597ca-e141-4468-ac31-1fd8dabef7a4
DTSTAMP;TZID=America/New_York:20220525T170804
LOCATION:1600 Pennsylvania Avenue NW\, Washington\, DC 20500
DESCRIPTION:Meet with the President
SUMMARY:Very important meeting
END:VEVENT
END:VCALENDAR
';

        $actual = $ics->generateIcs("Very important meeting", "Meet with the President", "2010-02-06 19:30:13", null, "1600 Pennsylvania Avenue NW, Washington, DC 20500");
        // exclude UID and DTSTAMP
        $excludeArr = [6, 7];
        $this->testIcsEqual($expect, $actual, $excludeArr);
    }
}
