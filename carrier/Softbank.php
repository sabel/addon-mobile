<?php

/**
 * Mobile_Carrier_Softbank
 *
 * @category   Addon
 * @package    addon.mobile
 * @author     Yutaka Ebine <yutaka@ebine.org>
 * @copyright  2004-2010 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class Mobile_Carrier_Softbank extends Mobile_Carrier_Abstract
{
  public function __construct(array $config)
  {
    $this->carrierId = MOBILE_SOFTBANK_ID;
    
    $this->config = $config;
    $this->pictgram = Mobile_Pictgram_Softbank::getInstance($config["charset"]);
  }
  
  public function isSoftbank()
  {
    return true;
  }
  
  public function getMobileId()
  {
    if (isset($_SERVER["HTTP_X_JPHONE_UID"])) {
      return $_SERVER["HTTP_X_JPHONE_UID"];
    } else {
      return (isset($_SERVER["REMOTE_ADDR"])) ? $_SERVER["REMOTE_ADDR"] : "127.0.0.1";
    }
  }
  
  public function normalInput($name, $value = "", $type = "text", $size = null)
  {
    $attrs = $this->createInputAttributes($name, $value, $size);
    
    return '<input type="' . $type . '" istyle="1" format="*M" mode="hiragana" ' . $attrs . '/>';
  }
  
  public function kanaInput($name, $value = "", $type = "text", $size = null)
  {
    $attrs = $this->createInputAttributes($name, $value, $size);
    
    return '<input type="' . $type . '" istyle="2" format="*M" mode="hankakukana" ' . $attrs . '/>';
  }
  
  public function alphaInput($name, $value = "", $type = "text", $size = null)
  {
    $attrs = $this->createInputAttributes($name, $value, $size);
    
    return '<input type="' . $type . '" istyle="3" format="*m" mode="alphabet" ' . $attrs . '/>';
  }
  
  public function numberInput($name, $value = "", $type = "text", $size = null)
  {
    $attrs = $this->createInputAttributes($name, $value, $size);
    
    return '<input type="' . $type . '" istyle="4" format="*N" mode="numeric" ' . $attrs . '/>';
  }
  
  public function hr($color = "#999999", $bgColor = "#ffffff", $size = "1")
  {
    return '<hr size="' . $size . '" style="margin: 2px 0; padding: 0; ' .
           'border-style: solid; border-color: ' . $bgColor . '; ' .
           'width: 100%; background-color: ' . $color . ';" />';
  }
}
