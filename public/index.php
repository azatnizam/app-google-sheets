<?php
require(__DIR__ . '/../vendor/autoload.php');
$config = include(__DIR__ . "/../env/env.php");

echo "<h3>Google Sheets API</h3>";
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/../env/' . $config["CREDENTIALS_JSON"]);

$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->addScope("https://www.googleapis.com/auth/spreadsheets");

$sheetsService = new Google_Service_Sheets($client);

try {
 
    // $response = $sheetsService->spreadsheets_values->get("1bwC2qGEJ0oU2ZLUa_kyQAEVBmlS5lybaKfJu9NDhYQo", "2 сентября!A1:C22");
    $response = $sheetsService->spreadsheets_values->get("1DTdFIKfifM2pNiEZZ9dlemRNTBLumNm9KXXRZRTV9vY", "Выполнено!A1:C22");

    // $response = $sheetsService->spreadsheets->get("1bwC2qGEJ0oU2ZLUa_kyQAEVBmlS5lybaKfJu9NDhYQo");
    // echo "<p>" . $response->getProperties()->title . "</p>";

    echo "<pre>";
    print_r($response["values"]);
    echo "</pre>";
 
} catch (Google_Service_Exception $exception) {
 
    // Список всех возможных ошибок и исключений https://developers.google.com/drive/v3/web/handle-errors
    // Получаем информацию о причине возникновения исключения
    // $reason = $exception->getErrors()[0]['reason'];

    echo "<pre style='background: #ff6969'>";
    print_r($exception);
    echo "</pre>";
 
}
