<?php
/**
 * Check if a given ip is in a network
 * @param  string $ip    IP to check in IPV4 format eg. 127.0.0.1
 * @param  string $range IP/CIDR netmask eg. 127.0.0.0/24, also 127.0.0.1 is accepted and /32 assumed
 * @return boolean true if the ip is in this range / false if not.
 */
function ip_in_range( $ip, $range ) {
	if ( strpos( $range, '/' ) == false ) {
		$range .= '/32';
	}
	// $range is in IP/CIDR format eg 127.0.0.1/24
	list( $range, $netmask ) = explode( '/', $range, 2 );
	$range_decimal = ip2long( $range );
	$ip_decimal = ip2long( $ip );
	$wildcard_decimal = pow( 2, ( 32 - $netmask ) ) - 1;
	$netmask_decimal = ~ $wildcard_decimal;
	return ( ( $ip_decimal & $netmask_decimal ) == ( $range_decimal & $netmask_decimal ) );
}

function load_csv($file) {
	$file = fopen($file,"r");
	$data = [];
	while (!feof($file)) {
		$data[] = (fgetcsv($file));
	}
	fclose($file);
	return $data;
}
ini_set('memory_limit', -1);
if (count($argv) > 1) {
	$ip = $argv[1];
	$ipData = load_csv(__DIR__ . '/data/GeoLite2-Country-Blocks-IPv4.csv');
	$countryData = load_csv(__DIR__ . '/data/GeoLite2-Country-Locations-en.csv');
	$coutryId = null;
	foreach ($ipData as $ipRow) {
		$subnet = $ipRow[0];
		if (ip_in_range($ip, $subnet)) {
			$coutryId = $ipRow[1];
			break;
		}
	}
	if (!empty($coutryId)) {
		foreach ($countryData as $countryRow) {
			if($countryRow[0] === $coutryId) {
				echo $countryRow[4] . PHP_EOL;
				exit;
			}
		}
	}
	echo 'Can not found country for this IP' . PHP_EOL;
}