<?php

/**
 * Mobile_Pictgram_Abstract
 *
 * @category   Addon
 * @package    addon.mobile
 * @author     Yutaka Ebine <yutaka@ebine.org>
 * @copyright  2004-2010 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
abstract class Mobile_Pictgram_Abstract extends Sabel_Object
{
  protected $pictmap = array();
  protected $charset = "";
  
  abstract public function convertToInternalCode($str);
  
  public static function getCSVDir()
  {
    return dirname(__FILE__) . DS . "csv";
  }
  
  public static function getWebToMobileMap()
  {
    $map = array();
    
    foreach (file(self::getCSVDir() . DS . "web.csv") as $line) {
      $line = trim($line);
      if (empty($line)) continue;
      
      list ($wc, $d, $e, $s) = explode(",", $line);
      
      $map[$wc] = array(
        MOBILE_DOCOMO_ID   => $d,
        MOBILE_EZWEB_ID    => $e,
        MOBILE_SOFTBANK_ID => $s,
      );
    }
    
    return $map;
  }
  
  public function getMobileToWebMap()
  {
    $map = array();
    
    foreach (file($this->getCSVFilePath()) as $line) {
      $line = trim($line);
      if (empty($line)) continue;
      
      list ($mc, $wc) = explode(",", $line);
      $map[$mc] = $wc;
    }
    
    return $map;
  }
  
  public function getCSVFilePath()
  {
    return self::getCSVDir() . DS . $this->csvFileName;
  }
  
  protected function replacePictgram($matches)
  {
    if (empty($this->pictmap)) {
      $this->pictmap = $this->getMobileToWebMap();
    }
    
    $str = "";
    $code = strtoupper(bin2hex($matches[0]));
    
    if (isset($this->pictmap[$code])) {
      $str = $this->pictmap[$code];
      if (preg_match('/^[0-9]{3}$/', $str) === 1) {
        return "{e:" . $str . "}";
      }
    } else {
      $str = "〓";
    }
    
    if ($this->charset === APP_ENCODING) {
      return $str;
    } else {
      return mb_convert_encoding($str, $this->charset, APP_ENCODING);
    }
  }
}
