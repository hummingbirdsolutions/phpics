<?php 
declare(strict_types=1);

namespace HummingbirdSolutions\Phpics;

# https://www.ietf.org/rfc/rfc5545.txt
final class Index
{
    // Example: "2010-02-06 19:30:13";
    private function sqlDateToIcsDateTimeFormat($mysqldate) {
        // $mysqldate = "2010-02-06 19:30:13";
        $phpdate = strtotime( $mysqldate );
        $icsdate = date ('Ymd\THis', $phpdate);
        return $icsdate;
    }

    // Example: "2010-02-06 19:30:13";
    private function sqlDateToIcsDateFormat($mysqldate) {
        $phpdate = strtotime( $mysqldate );
        $icsdate = date ('Ymd', $phpdate);
        return $icsdate;
    }

    private function encodeIcsText($str) {
        return addcslashes($str, "\\;,");
    }

    // Generate a universally unique identifier
    // Creates a type 4 UUID (aka GUID in Microsoft land)
    public static function generateUUIDv4()
    {
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    // address is optional
    // If datened not supplied, assume 1 full calender day taking into account the startdate parameter
    public function generateIcs(
        $title = "Meeting",
        $description = null,
        $datestart = null, # mysql date
        $dateend = null, # mysql date
        $address = null)
    {
        if (!isset($datestart)) {
            throw new \Exception("The start date is required");
        }

        if (!isset($description)) {
            throw new \Exception("A description is required");
        }

        if (!isset($title)) {
            throw new \Exception("A title is required");
        }

        $tzid = 'America/New_York';

        $icslocation = '';

        if (isset($address)) {
            $icslocation = '
LOCATION:' . $this->encodeIcsText($address);
        }

        $icsdate = '';

        if (isset($dateend)) {
            $icsdate = 'DTSTART;TZID=' . $tzid . ':' . $this->sqlDateToIcsDateTimeFormat($datestart) . '
DTEND;TZID=' . $tzid . ':' . $this->sqlDateToIcsDateTimeFormat($dateend);
        } else {
            $icsdate = 'DTSTART;TZID=' . $tzid . ':' . $this->sqlDateToIcsDateFormat($datestart);
        }

        $icsFileText = 'BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//Hummingbird Solutions//NONSGML PHP ICS//EN
CALSCALE:GREGORIAN
BEGIN:VEVENT
' . $icsdate . '
UID:' . $this->generateUUIDv4() . '
DTSTAMP;TZID=' . $tzid . ':' . date ('Ymd\THis', time()) . $icslocation . '
DESCRIPTION:' . $this->encodeIcsText($description) . '
SUMMARY:' . $this->encodeIcsText($title) . '
END:VEVENT
END:VCALENDAR
';

        return $icsFileText;
    }
}

// $ics = new Index();

// echo $ics->generateIcs("Very important meeting", "Meet with the President", "2010-02-06 19:30:13", "2010-02-06 19:50:13", "1600 Pennsylvania Avenue NW, Washington, DC 20500");

?>