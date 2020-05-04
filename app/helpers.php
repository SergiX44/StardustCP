<?php

if (!function_exists('isPrivateIP')) {
    /**
     * @param  string  $ip
     * @return bool
     */
    function isPrivateIP(string $ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
    }
}

if (!function_exists('isIpv6')) {
    /**
     * @param  string  $ip
     * @return bool
     */
    function isIpv6(string $ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;
    }
}
