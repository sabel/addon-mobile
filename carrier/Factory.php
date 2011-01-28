<?php

/**
 * Mobile_Carrier_Factory
 *
 * @category   Addon
 * @package    addon.mobile
 * @author     Yutaka Ebine <yutaka@ebine.org>
 * @copyright  2004-2010 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class Mobile_Carrier_Factory
{
  const JUDGE_BY_IP = "IP";
  const JUDGE_BY_UA = "UA";
  
  public static function create(Sabel_Config $config, $judgeBy = self::JUDGE_BY_IP, $clientIp = null)
  {
    if (empty($clientIp)) {
      $clientIp = (isset($_SERVER["REMOTE_ADDR"])) ? $_SERVER["REMOTE_ADDR"] : "127.0.0.1";
    }
    
    $carrier = "";
    $config = $config->configure();
    
    if ($judgeBy === self::JUDGE_BY_IP) {
      @include_once ("Net" . DS . "IPv4.php");
      
      if (!class_exists("Net_IPv4", false)) {
        throw new Sabel_Exception_ClassNotFound("Net_IPv4 (pear)");
      }
      
      foreach ($config["IP_TABLES"] as $_carrier => $ipaddrs) {
        foreach ($ipaddrs as $ipaddr) {
          if (@Net_IPv4::ipInNetwork($clientIp, $ipaddr)) {
            $carrier = $_carrier;
            break 2;
          }
        }
      }
    } elseif ($judgeBy === self::JUDGE_BY_UA) {
      @include_once ("Net" . DS . "UserAgent" . DS . "Mobile.php");
      
      if (!class_exists("Net_UserAgent_Mobile", false)) {
        throw new Sabel_Exception_ClassNotFound("Net_UserAgent_Mobile (pear)");
      }
      
      $agent = @Net_UserAgent_Mobile::singleton();
      
      if ($agent->isDocomo()) {
        $carrier = MOBILE_DOCOMO_ID;
      } elseif ($agent->isEZweb()) {
        $carrier = MOBILE_EZWEB_ID;
      } elseif ($agent->isSoftBank()) {
        $carrier = MOBILE_SOFTBANK_ID;
      }
    } else {
      $message = __METHOD__ . "() an illegal judgment method.";
      throw new Sabel_Exception_InvalidArgument($message);
    }
    
    switch ($carrier) {
      case MOBILE_DOCOMO_ID:
        return new Mobile_Carrier_Docomo($config[MOBILE_DOCOMO_ID]);
      case MOBILE_EZWEB_ID:
        return new Mobile_Carrier_Ezweb($config[MOBILE_EZWEB_ID]);
      case MOBILE_SOFTBANK_ID:
        return new Mobile_Carrier_Softbank($config[MOBILE_SOFTBANK_ID]);
      default:
        return new Mobile_Carrier_Others($config[MOBILE_OTHERS_ID]);
    }
  }
}
