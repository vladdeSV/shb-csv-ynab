# Handelsbanken export file to YNAB CSV
Convert a "Svenska Handelsbanken" export file to a CSV file which YNAB understands.

## Usage

Requires `php` and `composer`

```sh
composer install
php extract.php [filename] # filename defaults to 'kontotransactionlist.xls'
                           # outputs file 'ynab.csv'
```

## Todo
- [ ] Support different filenames
- [ ] Only output cleared transactions
- [ ] Select date span, or atleast "only select transactions from date ___"
