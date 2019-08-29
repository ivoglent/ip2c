<?php

use ivoglent\ip2c\IpLocator;

require 'vendor/autoload.php';
$ip = $argv[1];
$ipLocator = new IpLocator($ip);
print_r($ipLocator->analysis());exit;
if ($ipLocator->analysis()) {
    echo sprintf('%s, %s, %s', $ipLocator->getArea(), $ipLocator->getCountryCode(), $ipLocator->getCoutryName()) . PHP_EOL;
} else {
    echo 'Can not detect this IP address' . PHP_EOL;
}