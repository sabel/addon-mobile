<?php

/**
 * Mobile_Pictgram_Others
 *
 * @category   Addon
 * @package    addon.mobile
 * @author     Yutaka Ebine <yutaka@ebine.org>
 * @copyright  2004-2010 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class Mobile_Pictgram_Others extends Mobile_Pictgram_Abstract
{
  private static $instance = null;
  
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
    return $str;
  }
  
  public function getMobileToWebMap()
  {
    return array();
  }
}
