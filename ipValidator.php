<?php

/*
 * Class for validating remote ip
 */

class ipValidator
{
    private $ipv4; // Array to keep all generated ipv4s
    private $ipv6; // Array to keep all generated ipv6s (Not used for now)
    private $remote; // Remote address of the client
    private $helper; // Config Helper set mode to helper class instead of direct messsage
    private $type; // Type of the input IP
    private $ipv4cidr; // Input IPV4 CIDRS
    private $ipv6cidr; // Input IPV6 CIDRS
    private $isOnEduroam; // Boolean for return value
    private $uid; // Client Mellon UID
    private $displayName; // Client Mellon Display name
    private $debug; // Debug flag

    /**
     *
     * Construct entry point that creates all required stuff
     * @param $debug
     * @param $helper
     * @param $ipv4cidr
     * @param $ipv6cidr
     */
    function __construct($debug, $helper, $ipv4cidr, $ipv6cidr)
    {
        $this->debug = $debug;
        $this->uid = $_SERVER['MELLON_uid'];
        $this->displayName = $_SERVER['MELLON_displayName'];
        $this->remote = $_SERVER['REMOTE_ADDR'];
        $this->ipv4 = [];
        $this->ipv6 = [];
        $this->ipv4cidr = $ipv4cidr;
        $this->ipv6cidr = $ipv6cidr;
        $this->helper = $helper;
        $this->isOnEduroam = false;
        $this->type = $this->checkRemoteType($this->remote);
        if ($this->debug) {
            echo "<h1>Your IP: " . $this->remote . "  of type " . $this->type . " </h1>";
        }
    }


    /**
     * Check remote address type and return result
     * @param $remote
     * @return string|void
     */
    private function checkRemoteType($remote)
    {
        if (filter_var($remote, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return "ipv4";
        } elseif (filter_var($remote, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return "ipv6";
        }
    }

    /**
     * Generate all ipv4 addresses in the defined ipv4cidr array
     * @param $cidrArray
     * @return array
     */
    private function generateIPV4Addresses($cidrArray)
    {
        $ipv4addresses = [];
        foreach ($cidrArray as $cidr) {
            $ipv4addresses = array_merge($ipv4addresses, $this->getEachIpInRange($cidr));
        }
        return $ipv4addresses;
    }

    /**
     * Check if user is on eduroam or not and reutnr value or print out a message
     * @return bool|void
     */
    public function checkIfUserIsOnEduroam()
    {
        // Boolean gets set to true if remote address matches any

        // Check IPV6 addresses
        if ($this->type === "ipv6") {
            foreach ($this->ipv6cidr as $ip6) {
                if (strpos($this->remote, $ip6) === 0) {
                    $this->isOnEduroam = true;
                }
            }
        }

        // Check IPV6 Addresses
        if ($this->type === "ipv4") {
            $this->ipv4 = $this->generateIPV4Addresses($this->ipv4cidr);
            foreach ($this->ipv4 as $ip) {
                if ($this->remote == $ip) {
                    $this->isOnEduroam = true;
                }
            }
        }

        // If user is on eduroam greet them if not tell them they are not
        // If helper is true just return True / False
        if ($this->isOnEduroam) {
            if (!$this->helper) {
                echo "<h1>Hello " . $this->displayName . " uid: " . $this->uid . "</h1>";
                echo nl2br("\r\n<button>Press here to register for kursen XYZ</button");
                echo nl2br("\r\n");
            } else {
                return true;
            }

        } else {
            if (!$this->helper) {
                echo nl2br("You are not on eduroam");
            } else {
                return false;
            }
        }
    }

    /**
     * Generate all ipv4's in input cidr range
     * @param $cidr
     * @return array
     */
    private function getIpRange($cidr)
    {

        list($ip, $mask) = explode('/', $cidr);

        $maskBinStr = str_repeat("1", $mask) . str_repeat("0", 32 - $mask); //net mask binary string
        $inverseMaskBinStr = str_repeat("0", $mask) . str_repeat("1", 32 - $mask); //inverse mask

        $ipLong = ip2long($ip); // Convert ip to integher
        $ipMaskLong = bindec($maskBinStr); // Convert mask to decimal
        $inverseIpMaskLong = bindec($inverseMaskBinStr); // convert invert mask to decimal
        $netWork = $ipLong & $ipMaskLong; // join mask and ip

        $start = $netWork + 1; //ignore network ID(eg: 192.168.1.0)

        $end = ($netWork | $inverseIpMaskLong) - 1; //ignore broadcast IP(eg: 192.168.1.255)
        return array('firstIP' => $start, 'lastIP' => $end); // Return first and last in range
    }

    /**
     * Entry point for ipv4 array creation
     * @param $cidr
     * @return array
     */
    private function getEachIpInRange($cidr)
    {
        $ips = array();
        $range = $this->getIpRange($cidr);
        /**
         * Go through the array of integer ips from getIpRange and add them to an array for return while converting them back to IPV4:s
         */
        for ($ip = $range['firstIP']; $ip <= $range['lastIP']; $ip++) {
            $ips[] = long2ip($ip);
        }
        return $ips;
    }
}