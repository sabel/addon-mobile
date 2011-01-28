<?php

/**
 * Mobile_Object
 *
 * @category   Addon
 * @package    addon.mobile
 * @author     Yutaka Ebine <yutaka@ebine.org>
 * @copyright  2004-2010 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class mb
{
  /**
   * @var Mobile_Carrier_Abstract
   */
  protected static $carrier = null;

  protected static $pictmap = array();
  
  public static function setup(Mobile_Carrier_Abstract $carrier)
  {
    self::$carrier = $carrier;
  }
  
  public static function get_carrier()
  {
    return self::$carrier;
  }
  
  public static function get_carrier_id()
  {
    return self::$carrier->getCarrierId();
  }
  
  public static function get_pictgram()
  {
    return self::$carrier->getPictgram();
  }
  
  public static function is_docomo()
  {
    return self::$carrier->isDocomo();
  }
  
  public static function is_ezweb()
  {
    return self::$carrier->isEzweb();
  }
  
  public static function is_softbank()
  {
    return self::$carrier->isSoftbank();
  }
  
  public static function is_mobile()
  {
    return self::$carrier->isMobile();
  }
  
  public static function get_doctype()
  {
    return self::$carrier->getDocType();
  }
  
  public static function get_content_type()
  {
    return self::$carrier->getContentType();
  }
  
  public static function get_mobile_id()
  {
    return self::$carrier->getMobileId();
  }
  
  public static function get_charset()
  {
    return self::$carrier->getCharset();
  }
  
  public static function to_client_charset($str)
  {
    return mb_convert_encoding($str, self::get_charset(), APP_ENCODING);
  }
  
  public static function get_fontsize($size = "")
  {
    return self::$carrier->getFontSize($size);
  }
  
  public static function strwidth($str)
  {
    $str = preg_replace("/\{e:[0-9]{3}\}/", "__", $str);
    
    return mb_strwidth($str);
  }
  
  public static function stromit($str, $charCount, $marker = "…")
  {
    $charCount *= 2;
    
    $str = str_replace(array("\r", "\n"), "", $str);
    $tmp = preg_replace('/\{e:[0-9]{3}\}/', "\000\000", $str, -1, $pgCount);
    
    if ($pgCount >= 1 && ($pgCount = substr_count(mb_strimwidth($tmp, 0, $charCount), "\000\000")) > 0) {
      $tmp = mb_strimwidth($tmp, 0, $charCount);
      
      if (substr($tmp, -1) === "\000" && substr($tmp, -2) !== "\000\000") {
        $tmp = substr($tmp, 0, -1);
      }
      
      $width = mb_strwidth($tmp) + $pgCount * 7 - $pgCount * 2;
      $trimmed = mb_strimwidth($str, 0, $width);
    } else {
      $trimmed = mb_strimwidth($str, 0, $charCount);
    }
    
    return ($trimmed === $str) ? $str : $trimmed . $marker;
  }
  
  public static function input_normal($name, $value = "", $type = "text", $size = null)
  {
    return self::$carrier->normalInput(
      $name, self::output($value, false, true), $type, $size
    );
  }
  
  public static function input_kana($name, $value = "", $type = "text", $size = null)
  {
    return self::$carrier->kanaInput(
      $name, self::output($value, false, true), $type, $size
    );
  }
  
  public static function input_alpha($name, $value = "", $type = "text", $size = null)
  {
    return self::$carrier->alphaInput(
      $name, self::output($value, false, true), $type, $size
    );
  }
  
  public static function input_number($name, $value = "", $type = "text", $size = null)
  {
    return self::$carrier->numberInput(
      $name, self::output($value, false, true), $type, $size
    );
  }
  
  public static function textarea($name, $value = "", $rows = 5)
  {
    return '<textarea rows="' . $rows . '" name="' . $name . '">' .
           self::output($value, false, true) . '</textarea>';
  }
  
  public static function hr($color = "#999999", $bgColor = "#ffffff", $size = "1")
  {
    return self::$carrier->hr($color, $bgColor, $size);
  }
  
  public static function marquee($bgColor, $textColor, $text, $fontSize = null)
  {
    if (empty($fontSize)) {
      $fontSize = self::get_fontsize();
    }
    
    if (self::is_mobile()) {
      return '<div class="marquee" style="display: -wap-marquee; -wap-marquee-style: scroll; '
           . '-wap-marquee-dir: rtl; -wap-marquee-loop: 32; background-color: ' . $bgColor . ';">'
           . '<span style="color: ' . $textColor . '; font-size: ' . $fontSize . ';">' . $text
           . '</span></div>';
    } else {
      return '<marquee style="background-color: ' . $bgColor . ';">'
           . '<span style="color: ' . $textColor . ';">' . $text . '</span>'
           . '</marquee>';
    }
  }
  
  public static function pictgram($webCode)
  {
    $map = self::get_pictgram_map();
    
    switch ($carrier = self::$carrier->getCarrierId()) {
      case MOBILE_DOCOMO_ID:
      case MOBILE_EZWEB_ID:
      case MOBILE_SOFTBANK_ID:
        if (isset($map[$webCode][$carrier])) {
          return $map[$webCode][$carrier];
        } else {
          return "〓";
        }
      default:
        if (isset($map[$webCode])) {
          return '<img class="pictgram" src="/images/emoji/' . $webCode . '.gif" alt="" />';
        } else {
          return "〓";
        }
    }
  }
  
  public static function output($str, $toHalfWidth = true, $forInput = false, $escape = true)
  {
    if (is_empty($str)) {
      return "";
    } elseif ($escape) {
      $str = htmlescape($str);
    }
    
    $map = self::get_pictgram_map();
    
    if (preg_match_all('/\{e:[0-9]{3}\}/', $str, $matches) > 0) {
      switch ($carrier = self::$carrier->getCarrierId()) {
        case MOBILE_DOCOMO_ID:
        case MOBILE_EZWEB_ID:
        case MOBILE_SOFTBANK_ID:
          foreach ($matches[0] as $webCode) {
            $code = substr($webCode, 3, 3);
            if (isset($map[$code][$carrier])) {
              $str = str_replace($webCode, $map[$code][$carrier], $str);
            }
          }
          break;
        default:
          if (!$forInput) {
            foreach ($matches[0] as $webCode) {
              $code = substr($webCode, 3, 3);
              
              if (!$forInput) {
                $str = str_replace($webCode, '<img class="pictgram" src="/images/emoji/' . $code . '.gif" alt="" />', $str);
              }
            }
          }
      }
    }
    
    return ($toHalfWidth) ? mb_convert_kana($str, "ka") : $str;
  }
  
  public static function remove_pictcode($str)
  {
    return preg_replace("/\{e:[0-9]{3}\}/", "", $str);
  }
  
  public static function get_model()
  {
    $model = self::$carrier->toModelName(
      self::get_useragent_mobile()->getModel()
    );
    
    return (empty($model)) ? "UNKNOWN" : $model;
  }
  
  public static function get_useragent_mobile()
  {
    static $mobile = null;
    
    if ($mobile === null) {
      @require_once ("Net" . DS . "UserAgent" . DS . "Mobile.php");
      $mobile = @Net_UserAgent_Mobile::factory();
    }
    
    return $mobile;
  }
  
  public static function get_pictgram_map()
  {
    if (empty(self::$pictmap)) {
      self::$pictmap = Mobile_Pictgram_Abstract::getWebToMobileMap();
    }
    
    return self::$pictmap;
  }
  
  public static function is_mobile_email($email)
  {
    $mobileDomains = array(
      "docomo.ne.jp",
      "ezweb.ne.jp",
      "softbank.ne.jp",
      "vodafone.ne.jp",
      "disney.ne.jp",
      //"pdx.ne.jp",
      //"i.softbank.jp",
      //"emnet.ne.jp"
    );
    
    list (, $domain) = explode("@", $email);
    
    foreach ($mobileDomains as $mdomain) {
      if ($mdomain === substr($domain, -strlen($mdomain))) {
        return true;
      }
    }
    
    return false;
  }
  
  /**
   * alias
   */
  public static function get_doc_type()
  {
    return self::get_doctype();
  }
  
  /**
   * alias
   */
  public static function get_font_size()
  {
    return self::get_fontsize();
  }
}
