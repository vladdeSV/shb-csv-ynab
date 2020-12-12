<?php

require "vendor/autoload.php";

use JetBrains\PhpStorm\ArrayShape;
use PHPHtmlParser\Dom;

#[ArrayShape(['fromDate' => 'DateTimeImmutable|null'])]
function getOptions(): array
{
    $options = getopt('', ['from-date::']);
    $fromDate = null;

    if ($options['from-date'] ?? false) {
        try {
            $fromDate = new DateTimeImmutable($options['from-date']);
        } catch (Exception) {
            echo "Invalid date '{$options['from-date']}'";
        }
    }

    return [
        'fromDate' => $fromDate
    ];
}

$options = getOptions();

$dom = new Dom();
$dom->loadFromFile('kontotransactionlist.xls');
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

    if($options['fromDate'] && $transactionDate < $options['fromDate']) {
        continue;
    }

    $delta = str_replace([' ', ','], ['', '.'], $delta);

    $output .= "{$transactionDate->format('Y-m-d')},,\"" . str_replace('"', '\"', $note) . "\",$delta\n";
}

file_put_contents('ynab.csv', $output);
