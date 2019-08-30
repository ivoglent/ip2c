# IP to Country
Detect location of an IP live area, country code and country name.

# Usage
```php
require 'vendor/autoload.php';
use ivoglent\ip2c\IpLocator;

$ip = '186.95.255.255;
$ipLocator = new IpLocator($ip);
if ($ipLocator->analysis()) {
    echo sprintf('%s : %s, %s, %s', $ip, $ipLocator->getArea(), $ipLocator->getCountryCode(), $ipLocator->getCoutryName()) . PHP_EOL;
} else {
    echo 'Can not detect this IP address' . PHP_EOL;
}
```