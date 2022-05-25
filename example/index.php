<?php

require_once __DIR__ . '/vendor/autoload.php';

use \HummingbirdSolutions\Phpics\Index;

$ics = new Index();

echo $ics->generateIcs("Very important meeting", "Meet with the President", "2022-06-06 19:30:13", "2022-06-06 19:50:13", "1600 Pennsylvania Avenue NW, Washington, DC 20500");