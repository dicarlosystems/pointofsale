# Point Of Sale module for Invoice Ninja

This module adds the ability to use a barcode scanner to scan items into an invoice.

## Features
- Scanner dialog can be triggered manually using the button on the Invoices / Create or Invoices / Edit pages
- Automatically increments the quantity if the item already exists and the invoice line item has not been modified
- Updates all relevant product fields
- Allows documenting of serial number for serialized items
- Does not interfere with traditional invoice item entry

## Dependencies
This module requires the [Manufacturer](https://github.com/dicarlosystems/manufacturer) module to function.  Please install and run the migrations first!

## Updating Invoice Ninja
Depending on how Invoice Ninja was installed, you may need to perform additional steps:
- if installed from the downloaded zip, you will have to re-run the artisan command to inject the scanner
- if the source was cloned, eject the scanner before pulling the recent changes; otherwise you will get a conflict on the invoices view.  After the clone is complete you can re-inject the scanner as usual.

## Installation
Install the module like any other Invoice Ninja module:

```
php artisan module:install dicarlosystems/pointofsale --type=github-https
```

After the installation is complete, you must run the Artisan command to inject the view into the relevant Invoice Ninja views:
```
php artisan pointofsale:inject-scanner
```

To remove the scanner from the page(s), run the following command:
```
php artisan pointofsale:eject-scanner
```

## Issues / Feedback
All feedback or issues are welcome!  Feel free to open an issue for any bugs or feature requests.

## Screenshots

![Invoice page image](Assets/invoice_creation.png)

![Scanner dialog image](Assets/scanner_dialog.png)

