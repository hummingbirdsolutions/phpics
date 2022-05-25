<?php

require_once __DIR__ . '/vendor/autoload.php';

use \HummingbirdSolutions\Phpics\Index;

$ics = new Index();

echo $ics->generateIcs("Stuff", 99999999, 199999999, "http://www.hummingbirdsolutions.tech/calenderentry/987", "225 Jan Str, Appels, Centurion", "sghfgs", "Dessc");