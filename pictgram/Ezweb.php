<?php

/**
 * Mobile_Pictgram_Ezweb
 *
 * @category   Addon
 * @package    addon.mobile
 * @author     Yutaka Ebine <yutaka@ebine.org>
 * @copyright  2004-2010 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class Mobile_Pictgram_Ezweb extends Mobile_Pictgram_Abstract
{
  private static $instance = null;
  
  protected $csvFileName = "ezweb.csv";
  
  private function __construct($charset)
  {
    $this->charset = $charset;
  }
  
  public static function getInstance($charset)
  {
    if (self::$instance === null) {
      self::$instance = new self($charset);
    }
    
    return self::$instance;
  }
  
  public function convertToInternalCode($str)
  {
    if (is_empty($str)) {
      return "";
    }
    
    $ret = "";
    $charset = $this->charset;
    
    while (true) {
      if (is_empty($str)) break;
      
      $char = mb_substr($str, 0, 1, $charset);
      $str  = mb_substr($str, 1, mb_strlen($str, $charset), $charset);
      $ret .= preg_replace_callback("/[\xf3\xf4\xf6\xf7][\x40-\xfc]/", array($this, "replacePictgram"), $char);
    }
    
    return $ret;
  }
}
