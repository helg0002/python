<?php

// Инициализация CURL:
//$curl = curl_init();
//curl_setopt($curl, CURLOPT_URL, 'https://www.alphavantage.co/query?function=CURRENCY_EXCHANGE_RATE&from_currency=USD&to_currency=RUB&apikey=9DYJO320NVWGTHTM' );
//$response = curl_exec($curl);
//curl_close($curl);
//
//$json = json_decode($response);
//
//echo $json;
//Получить файл
$getJson = file_get_contents('https://min-api.cryptocompare.com/data/pricemultifull?fsyms=BTC,USD&tsyms=USD,ETH,RUB');
$jsonDec = json_decode($getJson,true);
unset($jsonDec['DISPLAY']);

function getCourse ($jsonDec, $from, $to)
{
//Сократить верхние уровни массива
    $getArray = array($jsonDec["RAW"]["$from"]["$to"]);
    //Присвоить значения
    foreach ($getArray as $key1 => $value) {
        $coin = $value["PRICE"];
        $time = $value["LASTUPDATE"];
    }
    //Создать новый массив с данными
    $json["datetime"] = strftime("%Y-%m-%d %H:%M:%S",$time);
    $json["price"] = $coin;
    return $json;
}

//Присвоить верхние уровни к массиву
$rate["BTC_USD"] = getCourse($jsonDec, 'BTC','USD');
$rate["BTC_ETH"] = getCourse($jsonDec, 'BTC','ETH');
$rate["USD_RUB"] = getCourse($jsonDec, 'USD','RUB');

$jsonFile = json_encode($rate,JSON_PRETTY_PRINT);


//Создать файл
//$file = fopen('rate_data.json','w+');
//fwrite($file, $jsonFile);
//
//print_r($rate);
$btc_usd = $rate["BTC_USD"]["price"];
$btc_eth = $rate["BTC_ETH"]["price"];
$usd_rub = $rate["USD_RUB"]["price"];

function createTable($db, $btc_usd, $btc_eth, $usd_rub) {
    $db->exec("CREATE TABLE curs(id INTEGER PRIMARY KEY, name TEXT, price INT)");
    $db->exec("INSERT INTO curs(name, price) VALUES('BTC/USD',$btc_usd)");
    $db->exec("INSERT INTO curs(name, price) VALUES('BTC/ETH', $btc_eth)");
    $db->exec("INSERT INTO curs(name, price) VALUES('USD/RUB', $usd_rub)");
}

$db = new SQLite3('rate.db');
if (file_exists("rate.db") === true){
    $db->exec("DELETE FROM curs");
    createTable($db, $btc_usd, $btc_eth, $usd_rub);
}else {
    print_r("Создание таблицы");
    createTable($db, $btc_usd, $btc_eth, $usd_rub);
}