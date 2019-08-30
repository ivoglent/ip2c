<?php


namespace ivoglent\ip2c;


class IpLocator
{
    const IPv4_MAP_FILE = __DIR__ . '/data/ipv4-map.csv';
    const IPv6_MAP_FILE = __DIR__ . '/data/ipv6-map.csv';
    const COUNTRY_REGISTERED_FILE = __DIR__ . '/data/registered-countries.csv';
    /**
     * @var string
     */
    private $ip;

    private $country;

    /**
     * IpLocator constructor.
     * @param $ip
     * @throws \Exception
     */
    public function __construct($ip)
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new \Exception('Invalid IP address.');
        }
        $this->ip = $ip;
    }

    /**
     * @return array|bool|false|null
     */
    public function analysis() {
        $countryId = $this->findInData();
        if (!empty($countryId)) {
            return $this->country = CsvHelper::findInCsv(self::COUNTRY_REGISTERED_FILE, $countryId, 0);
        }
        return false;
    }

    /**
     * @return string|null
     */
    public function getCountryCode() {
        if (!empty($this->country)) {
            return $this->country[2];
        }
        return null;
    }

    public function getArea() {
        if (!empty($this->country)) {
            return $this->country[1];
        }
        return null;
    }

    public function getCoutryName() {
        if (!empty($this->country)) {
            return $this->country[3];
        }
        return null;
    }

    /**
     * @return bool
     */
    private function findInData() {
        $countryId = null;
        CsvHelper::loadCsv(self::IPv4_MAP_FILE, function ($line) use(&$countryId) {
            if ($this->isIpInRange($this->ip, $line[0])) {
                $countryId = $line[1];
                return true;
            }
            return false;
        });
        return $countryId;
    }

    /**
     * @param $ip
     * @param $range
     * @return bool
     */
    private function isIpInRange( $ip, $range ) {
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
}