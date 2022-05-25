<?php 

namespace HummingbirdSolutions\Phpics;

# https://www.ietf.org/rfc/rfc5545.txt
class Index
{
    private function dateToIcsDateFormat($time) {
        return date('Ymd\This', $time) . 'Z';
    }

    public function generateIcs(
        $title = "Meeting",
        $datestart = null, # unix timestamp
        $dateend = null, # unix timestamp
        $url = "http://www.hummingbirdsolutions.tech/calenderentry/987",
        $address = null,
        $uid = null,
        $description = null)
    {
        if (!isset($datestart)) {
            throw new Exception("The start date is required");
        }

        if (!isset($dateend)) {
            throw new Exception("The end date is required");
        }
        
        # TODO: More validations here...

        $icsFileText = 'BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//Hummingbird Solutions//NONSGML PHP ICS//EN
CALSCALE:GREGORIAN
BEGIN:VEVENT
DTSTART:' . $this->dateToIcsDateFormat($datestart) . '
DTEND:' . $this->dateToIcsDateFormat($dateend) . '
UID:' . $uid . '
DTSTAMP:' . time() . '
LOCATION:' . addslashes($address) . '
DESCRIPTION:' . addslashes($description) . '
URL;VALUE=URI:' . $url . '
SUMMARY:' . addslashes($title) . '
END:VEVENT
END:VCALENDAR
';

        return $icsFileText;
    }
}

// $ics = new Index();

// echo $ics->generateIcs("Stuff", 99999999, 199999999, "http://www.hummingbirdsolutions.tech/calenderentry/987", "225 Jan Str, Appels, Centurion", "sghfgs", "Dessc");

?>