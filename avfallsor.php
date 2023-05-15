<?php

$addressidin = $_GET["addressid"];
$addressid = filter_var($addressidin, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

function formatDate($dateString) {
    $dateObject = new DateTime($dateString);
    return $dateObject->format('D j M Y');
}

function addleading0($dateString) {
    $dateObject = new DateTime($dateString);
    return $dateObject->format('Y-m-d');
}

function dateIsTodayOrTomorrow($datetotest) {
    $datetoday = 0;
    $today = date("Y-m-d");
    $tomorrow = date("Y-m-d", strtotime("+1 day"));
    if ($datetotest === $today || $datetotest === $tomorrow) {
        $datetoday = 1;
    }
    return $datetoday;
}

function dateIsToday($datetotest) {
    $datetoday = 0;
    $today = date("Y-m-d");
    $tomorrow = date("Y-m-d", strtotime("+1 day"));
    if ($datetotest === $today) {
        $datetoday = 1;
    }
    return $datetoday;
}

function dateIsTomorrow($datetotest) {
    $datetoday = 0;
    $today = date("Y-m-d");
    $tomorrow = date("Y-m-d", strtotime("+1 day"));
    if ($datetotest === $tomorrow) {
        $datetoday = 1;
    }
    return $datetoday;
}

function checkAvfallsor($addressid) {

    // Headers
    $headers0 = [
        "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/111.0.0.0 Safari/537.36",
    ];

    // Start Session
    $session = curl_init();

    // Prepare var for types

    // Avfall Sør
    // Rest - Restavfall
    // Bio - Bioavfall
    // Papp - Papp og papir
    // Plast - Plastemballasje
    // Both as called Papp, papir og plastemballasje in Avfall Sør
    // Glass - Glass- og metallemballasje
    $rest = "";
    $bio = "";
    $papp = "";
    $plast = "";
    $glass = "";

    # Get Avfall Sør HTML page
    $start_url = "https://avfallsor.no/henting-av-avfall/finn-hentedag/" . $addressid . "/";;
    $start_payload = [];

    curl_setopt($session, CURLOPT_URL, $start_url);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($session, CURLOPT_HTTPHEADER, $headers0);
    $start_response = curl_exec($session);

    $httpCode = curl_getinfo($session, CURLINFO_HTTP_CODE);
    // Check if the response code is 200
    if ($httpCode == 200) {
        //echo "Response code is 200. Success!";
    } else {
        echo "Error: " . $httpCode . " when attempting to connect to " . $start_url;
        exit;
    }

    $dom = new DOMDocument();
    @$dom->loadHTML($start_response);
    $forms = $dom->getElementsByTagName('form');
    $data = [];

    # For each form / Add to calender button
    foreach ($forms as $form) {
        $inputs = $form->getElementsByTagName('input');
        $form_data = [];

        $dtstart = "";
        $dtend = "";
        $description = "";
        $summary = "";

        // Get detail for each input in form / date and description
        foreach ($inputs as $input) {
            $name = $input->getAttribute('name');
            $value = $input->getAttribute('value');

            // set var for each part of event
            switch ($name) {
                case 'dtstart':
                    $dtstart = addleading0($value);
                    break;
                case 'dtend':
                    $dtend = addleading0($value);
                    break;
                case 'description':
                    $description = $value;
                    break;
                case 'summary':
                    $summary = $value;
                    break;
            }

        }

        # Set date for each description / garbage type
        switch ($description) {
            case 'Restavfall':
                if ($rest == "") {
                    $rest = $dtend;
                }
                break;
            case 'Bioavfall':
                if ($bio == "") {
                    $bio = $dtend;
                }
                break;
            case 'Papp, papir og plastemballasje':
                if ($papp == "") {
                    $papp = $dtend;
                    $plast = $dtend;
                }
                break;
            case 'Glass- og metallemballasje':
                if ($glass == "") {
                    $glass = $dtend;
                }
                break;
        }

    }

    # Format Dates
    $rest_formatted = formatDate($rest);
    $bio_formatted = formatDate($bio);
    $papp_formatted = formatDate($papp);
    $plast_formatted = formatDate($plast);
    $glass_formatted = formatDate($glass);

    // dateIsTodayOrTomorrow
    $rest_todayortomorrow = dateIsTodayOrTomorrow($rest);
    $bio_todayortomorrow = dateIsTodayOrTomorrow($bio);
    $papp_todayortomorrow = dateIsTodayOrTomorrow($papp);
    $plast_todayortomorrow = dateIsTodayOrTomorrow($plast);
    $glass_todayortomorrow = dateIsTodayOrTomorrow($glass);

    $rest_today = dateIsToday($rest);
    $bio_today = dateIsToday($bio);
    $papp_today = dateIsToday($papp);
    $plast_today = dateIsToday($plast);
    $glass_today = dateIsToday($glass);

    $rest_tomorrow = dateIsTomorrow($rest);
    $bio_tomorrow = dateIsTomorrow($bio);
    $papp_tomorrow = dateIsTomorrow($papp);
    $plast_tomorrow = dateIsTomorrow($plast);
    $glass_tomorrow = dateIsTomorrow($glass);

    return [$rest, $bio, $papp, $plast, $glass, $rest_formatted, $bio_formatted, $papp_formatted, $plast_formatted, $glass_formatted, $rest_todayortomorrow, $bio_todayortomorrow, $papp_todayortomorrow, $plast_todayortomorrow, $glass_todayortomorrow, $rest_today, $bio_today, $papp_today, $plast_today, $glass_today, $rest_tomorrow, $bio_tomorrow, $papp_tomorrow, $plast_tomorrow, $glass_tomorrow];
      
}

list($rest, $bio, $papp, $plast, $glass, $rest_formatted, $bio_formatted, $papp_formatted, $plast_formatted, $glass_formatted, $rest_todayortomorrow, $bio_todayortomorrow, $papp_todayortomorrow, $plast_todayortomorrow, $glass_todayortomorrow, $rest_today, $bio_today, $papp_today, $plast_today, $glass_today, $rest_tomorrow, $bio_tomorrow, $papp_tomorrow, $plast_tomorrow, $glass_tomorrow) = checkAvfallsor($addressid);

// Create into array and JSON
$result = [
    "rest" => $rest,
    "bio" => $bio,
    "papp" => $papp,
    "plast" => $plast,
    "glass" => $glass,
    "rest_formatted" => $rest_formatted,
    "bio_formatted" => $bio_formatted,
    "papp_formatted" => $papp_formatted,
    "plast_formatted" => $plast_formatted,
    "glass_formatted" => $glass_formatted,
    "rest_todayortomorrow" => $rest_todayortomorrow,
    "bio_todayortomorrow" => $bio_todayortomorrow,
    "papp_todayortomorrow" => $papp_todayortomorrow,
    "plast_todayortomorrow" => $plast_todayortomorrow,
    "glass_todayortomorrow" => $glass_todayortomorrow,
    "rest_today" => $rest_today,
    "bio_today" => $bio_today,
    "papp_today" => $papp_today,
    "plast_today" => $plast_today,
    "glass_today" => $glass_today,
    "rest_tomorrow" => $rest_tomorrow,
    "bio_tomorrow" => $bio_tomorrow,
    "papp_tomorrow" => $papp_tomorrow,
    "plast_tomorrow" => $plast_tomorrow,
    "glass_tomorrow" => $glass_tomorrow
];

// JSON string and output
echo json_encode($result, JSON_PRETTY_PRINT);

?>