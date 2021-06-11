Note: Only tested with Swedish Handelsbanken. This project is in alpha state. I use it a couple times a week, but it is not production ready. Use with caution.

# Handelsbanken to YNAB CSV converter
Convert a "Svenska Handelsbanken" export file to a CSV file which YNAB understands.

## How to use

1. Download project and extract (eg. `C:\Users\USER\Downloads\shb-csv-ynab-main\`)
2. Go to your bank account and download as Excel file ("Exportera till Excel", *kontotransactionlist.xls*)
3. Place *kontotransactionlist.xls* in the same diractory as the *extract.php* file
4. Open a terminal and naviagate to the script directory (eg. `cd "C:\Users\USER\Downloads\shb-csv-ynab-main\"`)
5. Run the command `php extract.php`
6. Import the newly created file `ynab.csv` into YNAB

## Script

Requires `php` and `composer`

```sh
composer install
```

```sh
php extract.php  # filename defaults to 'kontotransactionlist.xls'
                 # outputs file 'ynab.csv'
```

## Todo
- [ ] Support different filenames
- [ ] Only output cleared transactions
- [ ] Select date span, or at least "only select transactions from date ___"
