<?php
require(__DIR__ . '/../vendor/autoload.php');
$config = include(__DIR__ . "/../env/env.php");

putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/../env/' . $config["CREDENTIALS_JSON"]);

// Put here your new rows
$updValues = [
    ["1", "1", "1", "1", "1", "1", "1"],
    ["2", "2", "2", "2", "2", "2", "2"]
];

$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->addScope("https://www.googleapis.com/auth/spreadsheets");

$sheetsService = new Google_Service_Sheets($client);
$rangeBody = new Google_Service_Sheets_ValueRange( ["values" => $updValues] );

echo "<h3>Update Google Sheets via API</h3>";

try {
 
    // Calculating count of existing rows
    $response = $sheetsService->spreadsheets_values->get("1DTdFIKfifM2pNiEZZ9dlemRNTBLumNm9KXXRZRTV9vY", "Выполнено (копия)");
    $tableCurRowsCount = count($response["values"]);

    // Compose inserting range
    $updRowsCount = count($updValues);
    $updRange = ($tableCurRowsCount + 1) . ":" . ($tableCurRowsCount + 1 + $updRowsCount);

    // Debug: tracing updatable range
    // echo "2:" . $tableCurRowsCount . " - " .  ($tableCurRowsCount + 1) . ":" . ($tableCurRowsCount + $updRowsCount) . "<br />";

    $updResponse = $sheetsService->spreadsheets_values->update("1DTdFIKfifM2pNiEZZ9dlemRNTBLumNm9KXXRZRTV9vY", "Выполнено (копия)!" . $updRange, $rangeBody, ['valueInputOption' => 'RAW']);

    echo "<pre>";
    print_r($updResponse);
    echo "</pre>";
 
} catch (Google_Service_Exception $exception) {
 
    // Documentation: https://developers.google.com/drive/v3/web/handle-errors
    // Example: $reason = $exception->getErrors()[0]['reason'];

    $traceFilesCount = count($exception->getTrace());

    // Render error message
    echo "<div style='background: #ff6969; padding: 15px; border: 1px solid red;'>";
    echo "<b>[ Error ]</b> " . $exception->getErrors()[0]["message"] . "<br />";
    echo $exception->getTrace()[$traceFilesCount-1]["file"] . " (line: " . $exception->getTrace()[$traceFilesCount-1]["line"] . ")";
    echo "</div>";

    // Debug: show all Exeption fields
    // echo "<pre style='background: #ff6969'>";
    // print_r($exception);
    // echo "</pre>";
}
