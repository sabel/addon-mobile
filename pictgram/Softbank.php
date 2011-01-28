<?php

/**
 * Mobile_Pictgram_Softbank
 *
 * @category   Addon
 * @package    addon.mobile
 * @author     Yutaka Ebine <yutaka@ebine.org>
 * @copyright  2004-2010 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class Mobile_Pictgram_Softbank extends Mobile_Pictgram_Abstract
{
  private static $instance = null;
  
  protected $csvFileName = "softbank.csv";
  
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
      
      if ($char{0} === "\xee") {
        $ret .= preg_replace_callback(
          "/\xee[\x80\x81\x84\x85\x88\x89\x8c\x8d\x90\x91\x94][\x80-\xbf]/",
          array($this, "replacePictgram"),
          $char
        );
      } else {
        $ret .= $char;
      }
    }
    
    return $ret;
  }
}
