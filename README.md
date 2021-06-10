Note: This project is in alpha state. I use it a couple times a week, but it is not production ready. Use with caution.

# Handelsbanken to YNAB CSV converter
Convert a "Svenska Handelsbanken" export file to a CSV file which YNAB understands.

## Usage

Requires `php` and `composer`

```sh
composer install

php extract.php  # filename defaults to 'kontotransactionlist.xls'
                 # outputs file 'ynab.csv'
```

## Todo
- [ ] Support different filenames
- [ ] Only output cleared transactions
- [ ] Select date span, or at least "only select transactions from date ___"
