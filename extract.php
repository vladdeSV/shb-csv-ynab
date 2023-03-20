<?php

require "vendor/autoload.php";

use JetBrains\PhpStorm\ArrayShape;
use PHPHtmlParser\Dom;

// get all files ending in .html
$files = glob(dirname(__FILE__) . '/*.html');

// loop through files
foreach ($files as $file) {
    $filename = basename($file); // get file name without extension

    $dom = new Dom();
    $dom->loadFromFile($file);
    $rows = $dom->find('form > table')[3]->find('tr')->toArray();
    array_shift($rows);

    $output = "Date,Payee,Memo,Amount\n";
    foreach ($rows as $row) {
        $values = $row->find('td');

        $clearedDate = $values[0]->text;
        $transactionDate = new DateTimeImmutable($values[2]->text);
        $note = utf8_encode(html_entity_decode($values[4]->text));
        $delta = $values[6]->text;

        if ($clearedDate === '&nbsp;') {
            $clearedDate = '';
        }

        $delta = str_replace([' ', ','], ['', '.'], $delta);

        $output .= "{$transactionDate->format('Y-m-d')},,\"" . str_replace('"', '\"', $note) . "\",$delta\n";
    }

    file_put_contents($filename . '-ynab.csv', $output);
    unlink($file);
}
