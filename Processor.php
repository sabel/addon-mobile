<?php

/**
 * Mobile_Processor
 *
 * @category   Addon
 * @package    addon.mobile
 * @author     Yutaka Ebine <yutaka@ebine.org>
 * @copyright  2004-2010 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class Mobile_Processor extends Sabel_Bus_Processor
{
  protected $afterEvents = array(
    "router"     => "setup",
    "controller" => "assignValues",
    "view"       => "output",
  );
  
  protected $carrier = null;
  
  public function execute(Sabel_Bus $bus)
  {
    
  }
  
  public function setup($bus)
  {
    $this->carrier = $carrier = $this->getCarrier();
    
    mb::setup($carrier);
    
    if ($request = $bus->get("request")) {
      if ($gets = $request->fetchGetValues()) {
        foreach ($gets as $key => $value) {
          $request->setGetValue($key, $this->convertToInternalPictgramCode($carrier, $value));
        }
      }
      
      if ($posts = $request->fetchPostValues()) {
        foreach ($posts as $key => $value) {
          $request->setPostValue($key, $this->convertToInternalPictgramCode($carrier, $value));
        }
      }
    }
  }
  
  public function assignValues($bus)
  {
    if ($controller = $bus->get("controller")) {
      $carrier = $this->carrier;
      $controller->setAttribute("MB_DOCTYPE",      $carrier->getDocType());
      $controller->setAttribute("MB_CONTENT_TYPE", $carrier->getContentType());
      $controller->setAttribute("MB_FONTSIZE",     $carrier->getFontSize());
      $controller->setAttribute("MB_IS_MOBILE",    $carrier->isMobile());
    }
  }
  
  public function output($bus)
  {
    if ($response = $bus->get("response")) {
      $response->setHeader("Content-Type", mb::get_content_type());
    }
    
    if ($html = $bus->get("result")) {
      $bus->set("result", mb_convert_encoding($html, mb::get_charset(), APP_ENCODING));
    }
  }
  
  protected function getCarrier()
  {
    if ((ENVIRONMENT & PRODUCTION) > 0) {
      $judgeBy = Mobile_Carrier_Factory::JUDGE_BY_IP;
    } else {
      $judgeBy = Mobile_Carrier_Factory::JUDGE_BY_UA;
    }
    
    return Mobile_Carrier_Factory::create(new Mobile_Config(), $judgeBy);
  }
  
  protected function convertToInternalPictgramCode($carrier, $value)
  {
    if (is_array($value)) {
      foreach ($value as $k => $v) {
        if (is_array($v)) {
          $value[$k] = $this->convertToInternalPictgramCode($carrier, $v);
        } else {
          $value[$k] = $this->convertToInternalEncoding(
            $carrier->convertToInternalPictgramCode($v), $carrier->getCharset()
          );
        }
      }
      
      return $value;
    } else {
      return $this->convertToInternalEncoding(
        $carrier->convertToInternalPictgramCode($value), $carrier->getCharset()
      );
    }
  }
  
  protected function convertToInternalEncoding($value, $fromCharset)
  {
    if (is_string($value)) {
      return str_replace(
        array(
          "\xe3\x80\x9c",
          "\xe2\x88\x92",
          "\xe2\x80\x96",
          "\xc2\xa2",
          "\xc2\xa3",
          "\xc2\xac",
        ),
        array(
          "\xef\xbd\x9e",
          "\xef\xbc\x8d",
          "\xe2\x88\xa5",
          "\xef\xbf\xa0",
          "\xef\xbf\xa1",
          "\xef\xbf\xa2",
        ),
        mb_convert_encoding($value, APP_ENCODING, $fromCharset)
      );
    } else {
      return $value;
    }
  }
}
