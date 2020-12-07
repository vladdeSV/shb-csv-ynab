<?php

require "vendor/autoload.php";

use PHPHtmlParser\Dom;

$dom = new Dom;
$dom->loadFromFile('kontotransactionlist.xls');
$rows = $dom->find('form > table')[3]->find('tr')->toArray();
array_shift($rows);

$output = "Date,Payee,Memo,Amount\n";
foreach ($rows as $row) {
    $values = $row->find('td');

    $clearedDate = $values[0]->text;
    $transactionDate = $values[2]->text;
    $note = utf8_encode(html_entity_decode($values[4]->text));
    $delta = $values[6]->text;

    if ($clearedDate === '&nbsp;') {
        $clearedDate = '';
    }

    $delta = str_replace([' ', ','], ['', '.'], $delta);

    $output .= "$transactionDate,,\"" . str_replace('"', '\"', $note) . "\",$delta\n";
}

file_put_contents('ynab.csv', $output);
