<?php

/**
 * Mobile_Addon
 *
 * @category   Addon
 * @package    addon.mobile
 * @author     Yutaka Ebine <yutaka@ebine.org>
 * @copyright  2004-2010 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class Mobile_Addon extends Sabel_Object implements Sabel_Addon
{
  const VERSION = 1.0;
  
  public function execute(Sabel_Bus $bus)
  {
    require_once (dirname(__FILE__) . DS . "mb.php");
    
    define("MOBILE_DOCOMO_ID",   "docomo");
    define("MOBILE_EZWEB_ID",    "ezweb");
    define("MOBILE_SOFTBANK_ID", "softbank");
    define("MOBILE_OTHERS_ID",   "unknown");
    
    $bus->insertProcessor("request", new Mobile_Processor("mobile"), "after");
  }
}

function emoji($webCode)
{
  return mb::pictgram($webCode);
}
