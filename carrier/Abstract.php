<?php

/**
 * Mobile_Carrier_Abstract
 *
 * @category   Addon
 * @package    addon.mobile
 * @author     Yutaka Ebine <yutaka@ebine.org>
 * @copyright  2004-2010 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
abstract class Mobile_Carrier_Abstract extends Sabel_Object
{
  protected $carrierId = null;
  
  /**
   * @var Mobile_Pictgram_Abstract
   */
  protected $pictgram = null;
  
  /**
   * @var array
   */
  protected $config = array();
  
  abstract public function normalInput($name, $value = "", $type = "text", $size = null);
  abstract public function kanaInput($name, $value = "", $type = "text", $size = null);
  abstract public function alphaInput($name, $value = "", $type = "text", $size = null);
  abstract public function numberInput($name, $value = "", $type = "text", $size = null);
  abstract public function hr($color = "#999999", $size = "1", $bgColor = "#ffffff");
  
  abstract public function getMobileId();
  
  public function getCarrierId()
  {
    return $this->carrierId;
  }
  
  public function isDocomo()
  {
    return false;
  }
  
  public function isEzweb()
  {
    return false;
  }
  
  public function isSoftbank()
  {
    return false;
  }
  
  public function isMobile()
  {
    return true;
  }
  
  public function getCharset()
  {
    return $this->config["charset"];
  }
  
  public function getFontSize($size = "")
  {
    $config = $this->config;
    $key = "fontsize_" . strtolower($size);
    
    return (isset($config[$key])) ? $config[$key] : $config["fontsize"];
  }
  
  public function getDocType()
  {
    return <<<TEXT
<?xml version="1.0" encoding="{$this->config['output_charset']}"?>
{$this->config['doctype']}
TEXT;
  }
  
  public function getContentType()
  {
    return $this->config["content_type"] . "; charset=" . $this->config["output_charset"];
  }
  
  public function convertToInternalPictgramCode($str)
  {
    return $this->pictgram->convertToInternalCode($str);
  }
  
  public function toModelName($deviceId)
  {
    return $deviceId;
  }
  
  protected function createInputAttributes($name, $value, $size = null)
  {
    $attrs = 'name="' . $name . '" value="' . $value . '"';
    
    if ($size !== null) {
      $attrs .= ' size="' . $size . '"';
    }
    
    return $attrs;
  }
}
