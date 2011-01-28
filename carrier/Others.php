<?php

/**
 * Mobile_Carrier_Others
 *
 * @category   Addon
 * @package    addon.mobile
 * @author     Yutaka Ebine <yutaka@ebine.org>
 * @copyright  2004-2010 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class Mobile_Carrier_Others extends Mobile_Carrier_Abstract
{
  public function __construct(array $config)
  {
    $this->carrierId = MOBILE_OTHERS_ID;
    
    $this->config = $config;
    $this->pictgram = Mobile_Pictgram_Others::getInstance($config["charset"]);
  }
  
  public function isMobile()
  {
    return false;
  }
  
  public function getMobileId()
  {
    return (isset($_SERVER["REMOTE_ADDR"])) ? $_SERVER["REMOTE_ADDR"] : "127.0.0.1";
  }
  
  public function normalInput($name, $value = "", $type = "text", $size = null)
  {
    $attrs = $this->createInputAttributes($name, $value, $size);
    
    return '<input type="' . $type . '" ' . $attrs . '/>';
  }
  
  public function kanaInput($name, $value = "", $type = "text", $size = null)
  {
    $attrs = $this->createInputAttributes($name, $value, $size);
    
    return '<input type="' . $type . '" ' . $attrs . '/>';
  }
  
  public function alphaInput($name, $value = "", $type = "text", $size = null)
  {
    $attrs = $this->createInputAttributes($name, $value, $size);
    
    return '<input type="' . $type . '" ' . $attrs . '/>';
  }
  
  public function numberInput($name, $value = "", $type = "text", $size = null)
  {
    $attrs = $this->createInputAttributes($name, $value, $size);
    
    return '<input type="' . $type . '" ' . $attrs . '/>';
  }
  
  public function hr($color = "#999999", $bgColor = "#ffffff", $size = "1")
  {
    return '<div style="margin: 0.5em 0; border-top: ' . $size . 'px solid ' . $color . ';"></div>';
  }
}
