<?php
require '../vendor/autoload.php';
use ivoglent\ip2c\IpLocator;
$start = microtime(true);
$ip = $argv[1];
$ipLocator = new IpLocator($ip);
if ($ipLocator->analysis()) {
    echo sprintf('%s : %s, %s, %s', $ip, $ipLocator->getArea(), $ipLocator->getCountryCode(), $ipLocator->getCoutryName()) . PHP_EOL;
} else {
    echo 'Can not detect this IP address' . PHP_EOL;
}
$end = microtime(true);
echo sprintf('Executed in : %d ms', ($end - $start) * 1000 ) . PHP_EOL;