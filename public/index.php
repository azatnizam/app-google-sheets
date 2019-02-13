<?php
require(__DIR__ . '/../vendor/autoload.php');
$config = include(__DIR__ . "/../env/env.php");

putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/../env/' . $config["CREDENTIALS_JSON"]);

// Local environment
$config = [
    "spreadsheet_id" => "1DTdFIKfifM2pNiEZZ9dlemRNTBLumNm9KXXRZRTV9vY",
    "sheet_name" => "Выполнено (копия)",
    "update_range" => "A2", // Google api will update range from A2 to data length
];

// Put here your new rows
$updValues = [
    ["тест", "тест", "лорем", "1", "1", "1", "1"],
    ["тест", "тест", "лорем", "1", "1", "1", "1"],
    ["ага", "2", "2", "2", "2", "2", "2"]
];

$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->addScope("https://www.googleapis.com/auth/spreadsheets");

$sheetsService = new Google_Service_Sheets($client);
$rangeBody = new Google_Service_Sheets_ValueRange( ["values" => $updValues] );
$clearRequestBody = new Google_Service_Sheets_ClearValuesRequest();


echo "<h3>Update Google Sheets via API</h3>";

try {
    /* Variant #1: Total updating sheet */
    
    // Documentation: https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets.values/clear
    $clearResponse = $sheetsService->spreadsheets_values->clear($config["spreadsheet_id"], $config["sheet_name"] . "!" . "A2:G", $clearRequestBody);

    // Documentation: https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets.values/update
    $updResponse = $sheetsService->spreadsheets_values->update($config["spreadsheet_id"], $config["sheet_name"] . "!" . $config["update_range"], $rangeBody, ['valueInputOption' => 'RAW']);

    dump($clearResponse);


    /* Variant #2: Append data into end of sheet */
    /*
    // Documentation: https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets.values/append
    $updResponse = $sheetsService->spreadsheets_values->append($config["spreadsheet_id"], $config["sheet_name"] . "!" . $config["update_range"], $rangeBody, ['valueInputOption' => 'RAW']);
    */

    dump($updResponse);
 
} catch (Google_Service_Exception $exception) {
 
    // Documentation: https://developers.google.com/drive/v3/web/handle-errors
    // Example: $reason = $exception->getErrors()[0]['reason'];

    $traceFilesCount = count($exception->getTrace());

    // Render error message
    echo "<div style='background: #ff6969; padding: 15px; border: 1px solid red;'>";
    echo "<b>[ Error ]</b> " . $exception->getErrors()[0]["message"] . "<br />";
    echo $exception->getTrace()[$traceFilesCount-1]["file"] . " (line: " . $exception->getTrace()[$traceFilesCount-1]["line"] . ")";
    echo "</div>";
}
