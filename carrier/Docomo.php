<?php

/**
 * Mobile_Carrier_Docomo
 *
 * @category   Addon
 * @package    addon.mobile
 * @author     Yutaka Ebine <yutaka@ebine.org>
 * @copyright  2004-2010 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class Mobile_Carrier_Docomo extends Mobile_Carrier_Abstract
{
  public function __construct(array $config)
  {
    $this->carrierId = MOBILE_DOCOMO_ID;
    
    $this->config = $config;
    $this->pictgram = Mobile_Pictgram_Docomo::getInstance($config["charset"]);
  }
  
  public function isDocomo()
  {
    return true;
  }
  
  public function getMobileId()
  {
    if (isset($_SERVER["HTTP_X_DCMGUID"])) {
      return $_SERVER["HTTP_X_DCMGUID"];
    }
    
    if (isset($_SERVER["HTTP_USER_AGENT"])) {
      $ua = $_SERVER["HTTP_USER_AGENT"];
      preg_match('/\((.+)\)/', $ua, $matches);
      
      if (isset($matches[1])) {
        foreach (explode(";", $matches[1]) as $opt) {
          if (substr($opt, 0, 3) === "icc") {
            return $opt;
          }
        }
      }
    }
    
    return (isset($_SERVER["REMOTE_ADDR"])) ? $_SERVER["REMOTE_ADDR"] : "127.0.0.1";
  }
  
  public function normalInput($name, $value = "", $type = "text", $size = null)
  {
    $attrs = $this->createInputAttributes($name, $value, $size);
    
    return '<input type="' . $type . '" style="-wap-input-format:&quot;' .
           '*&lt;ja:h&gt;&quot;;-wap-input-format:*M;" ' . $attrs . '/>';
  }
  
  public function kanaInput($name, $value = "", $type = "text", $size = null)
  {
    $attrs = $this->createInputAttributes($name, $value, $size);
    
    return '<input type="' . $type . '" style="-wap-input-format:&quot;' .
           '*&lt;ja:hk&gt;&quot;;-wap-input-format:*M;" ' . $attrs . '/>';
  }
  
  public function alphaInput($name, $value = "", $type = "text", $size = null)
  {
    $attrs = $this->createInputAttributes($name, $value, $size);
    
    return '<input type="' . $type . '" style="-wap-input-format:&quot;' .
           '*&lt;ja:en&gt;&quot;;-wap-input-format:*m;" ' . $attrs . '/>';
  }
  
  public function numberInput($name, $value = "", $type = "text", $size = null)
  {
    $attrs = $this->createInputAttributes($name, $value, $size);
    
    return '<input type="' . $type . '" style="-wap-input-format:&quot;' .
           '*&lt;ja:n&gt;&quot;;-wap-input-format:*N;" ' . $attrs . '/>';
  }
  
  public function hr($color = "#999999", $bgColor = "#ffffff", $size = "1")
  {
    return '<hr style="margin: 2px 0; padding: 0; ' .
           'border-style: solid; border-color: ' . $bgColor . '; ' .
           'width: 100%; height: ' . $size . 'px; ' .
           'background-color: ' . $color . ';" />';
  }
}
