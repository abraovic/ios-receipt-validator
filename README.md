# abraovic/ios-receipt-validator

## Installation

The preferred method of installation is via [Packagist][] and [Composer][]. Run the following command to install the package and add it as a requirement to your project's `composer.json`:

```bash
composer require abraovic/ios-receipt-validator  // NOT AVAILABLE - STILL IN DEVELOPMENT
```

## Examples


```php
<?php
require 'vendor/autoload.php';

use abraovic\iOSReceiptValidator\Validate;
use abraovic\iOSReceiptValidator\Exception\iOSReceiptValidatorException;

try {
    $receipt = 'put-your-receipt-here';
    $sharedSecret = 'put-your-secret-here-if-any';

    // for sandbox mode
    Validate::$dev = true;

    $validate = new Validate($receipt, $sharedSecret);
    $response = $validate->execute(); // returns Response object
} catch (iOSReceiptValidatorException $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}

```

### Available methods for Response object

```php
$response->getStatus();
$response->getReceipt();  // returns Receipe object
$response->getLatestReceipt();
$response->getLatestReceiptInfo(); // returns Receipe object
```

### Available methods for Receipt object

```php
$receipt->getBundleId();
$receipt->getApplicationVersion();
$receipt->getInApp();
$receipt->getOriginalApplicationVersion();
$receipt->getCreationDate();
$receipt->getOriginalPurchaseDate();
```

## Contributing

Contributions are welcome! Please read [CONTRIBUTING][] for details.

## Copyright and license

The abraovic/ios-receipt-validator library is copyright © [Ante Braovic](http://antebraovic.me) and licensed for use under the Apache2 License.

[packagist]: https://packagist.org/packages/abraovic/ios-receipt-validator
[composer]: http://getcomposer.org/
[contributing]: https://github.com/abraovic/ios-receipt-validator/blob/master/CONTRIBUTORS.md