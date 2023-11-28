<?php 

namespace SysCore\PShop;

use syscore\Main;
use pocketmine\block\utils\SignText;

class PShop {
    
    public static function isExists($xid) {
        $dir = Main::getInstance()->getDataFolder()."pshops/";
        return file_exists($dir.$xid.".json");
    }
        
    public static function initShop($xid) {
        return new Shop($xid);
    }
    
    public static function loadSigns() {
        $shops = self::getShops();
        foreach ($shops as $shop) {
            $signs = $shop->loadSigns();
        }
    }
    
    public static function getShops() {
        $directory = Main::getInstance()->getDataFolder()."pshops/";
        $shops = [];
        
        if ($handle = opendir($directory)) {
            while (($file = readdir($handle)) !== false) {
                if (is_file($directory . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'json') {
                    $xid = pathinfo($file, PATHINFO_FILENAME);
                    $shops[] = new Shop($xid);            
                }
            }
            closedir($handle);
        }
        return $shops;
    }
    
    public static function getShopBySign($tile) {
        foreach (self::getShops() as $shop) {
            if ($shop->isSign($tile)) {
                return $shop;
            }
        }
        return null;
    }
    
    public static function getShopByStorage($tile) {
        foreach (self::getShops() as $shop) {
            if ($shop->isStorage($tile)) {
                return $shop;
            }
        }
        return null;
    }
    
}