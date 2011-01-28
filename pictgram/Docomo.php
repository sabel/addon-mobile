<?php

/**
 * Mobile_Pictgram_Docomo
 *
 * @category   Addon
 * @package    addon.mobile
 * @author     Yutaka Ebine <yutaka@ebine.org>
 * @copyright  2004-2010 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class Mobile_Pictgram_Docomo extends Mobile_Pictgram_Abstract
{
  private static $instance = null;
  
  protected $csvFileName = "docomo.csv";
  
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
      
      $pf = $char{0};
      if ($pf === "\xf8" || $pf === "\xf9") {
        $ret .= preg_replace_callback("/(\xf8[\x9f-\xfc])|(\xf9[\x40-\xfc])/", array($this, "replacePictgram"), $char);
      } else {
        $ret .= $char;
      }
    }
    
    return $ret;
  }
}
