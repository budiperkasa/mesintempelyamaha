<?php
defined('_VALID_MOS') or die('Restricted access');
if ( ! function_exists('shSSLAnalytics') ) {

    function shSSLAnalytics ($params)
    {
        $result = $script = '';
        $shGAUserCode    = $params->get('shGAUSerCode');
        $shGADetect      = $params->get('shGADetect');
        $shGATrackScript = $params->get('shGATrackScript');
        if ( empty($shGAUserCode) ) {
            return $result;
        }
        // set url style
        switch ( $shGADetect ) {
            case 'SSL':
                $shHttps = true;
                break;
            case 'nonSSL':
                $shHttps = false;
                break;
            default:
                $http_host = explode(':', $_SERVER['HTTP_HOST']);
                $shHttps = ((! empty($_SERVER['HTTPS']) 
                        && strtolower($_SERVER['HTTPS']) != 'off' 
                        || isset($http_host[1]) 
                        && $http_host[1] == 443));
                break;
        }
        // set script by tracking code
        switch ( $shGATrackScript ) {
            case 'ga':
                $script  = "var pageTracker = _gat._getTracker('$shGAUserCode'); ";
                $script .= 'pageTracker._initData(); ';
                $script .= 'pageTracker._trackPageview(); ';
                break;
            case 'urchin':
            default;
                $script  = "_uacct ='$shGAUserCode';";
                $script .= 'urchinTracker();';
            break;
        }
        // set code source url
        if ( $shHttps ) {
            $shURL = "https://ssl";
        } else {
            $shURL = "http://www";
        }
        $shURL .= ".google-analytics.com/$shGATrackScript.js";
        
        // create scripts 
        $result  = "<script src='$shURL' type='text/javascript'></script>";
        $result .= "<script type='text/javascript'>$script</script>";
        return $result;
    }
}
// begin main output //
$shGACode = shSSLAnalytics($params);
echo $shGACode;
?>