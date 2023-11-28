<?php 

namespace SysCore\PShop;

use SysCore\PData\PData;
use pocketmine\block\tile\Tile;
use pocketmine\utils\Config;
use pocketmine\item\Item;
use pocketmine\block\Grass;
use syscore\Main;
use pocketmine\math\Vector3;
use pocketmine\player\Player;;
use pocketmine\item\ItemIdentifier;
use pocketmine\world\Position;
use pocketmine\block\tile\Sign;
use SysCore\PMoney\PMoney;
use pocketmine\block\Barrel;
use pocketmine\block\WallSign;
use pocketmine\block\FloorSign;
use pocketmine\block\BaseSign;
use pocketmine\block\utils\SignText;
use pocketmine\block\tile\Barrel as TileBarrel;
class Shop {
    
    private $xid;
    
    public function __construct($xid) {
        $this->xid = $xid;
        $this->loadData();
    }
    
    private function loadData() {
        $dir = Main::getInstance()->getDataFolder()."pshops/";
        @mkdir($dir);
        return new Config($dir.$this->xid.".json", Config::JSON, [
            "xid" => $this->xid,
            "name" => $this->getPlayer()?->getName() || 'My Shop',
            "helpers" => [],
            "signs" => [],
            "storages" => [],
            "item-prices" => []
        ]);
    }
    
    public function getXid() {
        return $this->xid;
    }
    
    public function getOwnerName() {
        return PData::get($this->xid, "nickname");
    }
    
    public function isOwner(Player $player) {
        return $player->getXuid() == $this->getXid();
    }
    
    public function getPlayer() {
        return PData::getPlayerByXid($this->xid);
    }
    
    public function getName() {
        return $this->loadData()->get("name");
    }
    
    public function setName(string $name) {
        $config = $this->loadData();
        $config->set("name", $name);
        $config->save();
        return true;
    }
    
    public function getHelpers() {
        return $this->loadData()->get("helpers");
    }
    
    public function addHelper(Player $player) {
        $config = $this->loadData();
        $helpers = $config->get("helpers");
        $helpers[] = [
            "xid" => $player->getXuid(),
            "nickname" => $player->getName(),
        ];
        $config->set("helpers", $helpers);
        $config->save();
        return true;
    }
    
    public function isHelper(Player $player) {
        foreach ($this->getHelpers() as $helper) {
            if ($helper['xid'] == $player->getXuid()) {
                return true;
            }
        }
        return false;
    }
    
    public function getItems() {
        $items = [];
        foreach ($this->getStorages() as $storage) {
            $contents = $storage->getInventory()->getContents();
            foreach ($contents as $item) {
                if (!isset($items[$item->getName()])) {
                    $items[$item->getName()] = [
                        "item" => $item,
                        "count" => $item->getCount()
                    ];
                }else {
                    $items[$item->getName()]["count"] += $item->getCount();
                }
            }
        }
        return $items;
    }
    
    public function getStockItem($itemName) {
        $stock = 0;
        foreach ($this->getStorages() as $storage) {
            $contents = $storage->getInventory()->getContents();
            foreach ($contents as $item) {
                if ($item instanceof Item) {
                    if ($item->getName() == $itemName) {
                        $stock += $item->getCount();
                    }
                }
            }
        }
        return $stock;
    }
    
    public function getItemPrice(string $itemName) {
        $itemPrices = $this->loadData()->get("item-prices");
        return isset($itemPrices[$itemName]) ? $itemPrices[$itemName] : 0;
    }
    
    public function setItemPrice($itemName, int $amount) {
        $config = $this->loadData();
        $itemPrices = $config->get("item-prices");
        $itemPrices[$itemName] = $amount;
        $config->set("item-prices", $itemPrices);
        $config->save();
        return true;
    }
    
    public function setStorage(Barrel|TileBarrel $tile, string $name = null) {
        if (!$this->isStorage($tile)) {
            $config = $this->loadData();
            $storages = $config->get("storages");
            $pos = $tile->getPosition();
            $storages[] = [
                "worldId" => $pos->getWorld()->getId(),
                "posX" => $pos->getX(),
                "posY" => $pos->getY(),
                "posZ" => $pos->getZ(),
                "name" => $name
            ];
            $config->set("storages", $storages);
            $config->save();
            $this->loadSigns();
            return true;
        }
        return false;
    }
    
    public function getStorages() {
        $storages = $this->loadData()->get("storages");
        $tiles = [];
        foreach ($storages as $storage) {
            $world = Main::getInstance()->getServer()->getWorldManager()->getWorld($storage['worldId']);
            $tile = $world->getTileAt($storage['posX'], $storage['posY'], $storage['posZ']);
            $tiles[] = $tile;
        }
        return $tiles;
    }
    
    public function isStorage(Barrel|TileBarrel $tile) {
        foreach ($this->getStorages() as $storage) {
            $pos = $storage->getPosition();
            if ($pos->equals($tile->getPosition())) {
                return true;
            }
        }
        return false;
    }
    
    public function removeStorage(Barrel|TileBarrel $tile) {
        if ($this->isStorage($tile)) {
            $config = $this->loadData();
            $storages = $config->get("storages");
            $pos = $tile->getPosition();
            foreach ($storages as $i => $storage) {
                $world = Main::getInstance()->getServer()->getWorldManager()->getWorld($storage['worldId']); 
                $sPos = new Position($storage["posX"], $storage['posY'], $storage['posZ'], $world);
                if ($sPos->equals($pos)) {
                    unset($storages[$i]);
                }
            }
            $config->set("storages", $storages);
            $config->save();
            $this->loadSigns();
            return true;
        }
        return false;
    }
    
    public function isSign($tile) {
        if ($tile instanceof BaseSign) {
            $pos = $tile->getPosition();
            foreach ($this->getSigns() as $sign) {
                if ($sign->getPosition()->equals($pos)) {
                    return $sign;
                }
            }
        }
        return ;
    }
    
    public function setSign(BaseSign $tile, string $itemName) {
        if ($tile instanceof BaseSign) {
            if (!$this->isSign($tile)) {
                $config = $this->loadData();
                $signs = $config->get("signs");
                $pos = $tile->getPosition();
                $signs[] = [
                    "worldId" => $pos->getWorld()->getId(),
                    "posX" => $pos->getX(),
                    "posY" => $pos->getY(),
                    "posZ" => $pos->getZ(),
                    "itemName" => $itemName
                ];
                $config->set("signs", $signs);
                $config->save();
                return true;
            }
        }
        return false;
    }
    
    public function loadSigns() {
        $signs = $this->loadData()->get("signs");
        foreach ($signs as $sign) {
            $world = Main::getInstance()->getServer()->getWorldManager()->getWorld($sign['worldId']);
            $block = $world->getBlockAt($sign['posX'], $sign['posY'], $sign['posZ']);
            if ($block instanceof BaseSign) {
                $this->updateSign($block);
            }
        }
    }
    
    public function getSignData(BaseSign $block) {
        $signs = $this->loadData()->get("signs");
        $tiles = [];
        foreach ($signs as $sign) {
            $world = Main::getInstance()->getServer()->getWorldManager()->getWorld($sign['worldId']);
            $pos = new Position($sign['posX'], $sign['posY'], $sign['posZ'], $world);
            if ($block->getPosition()->equals($pos)) {
                return $sign;
            }
        }
        return $tiles;
    }
    
    public function updateSign(BaseSign $sign) {
        $pos = $sign->getPosition();
        $world = $pos->getWorld();
        
        $data = $this->getSignData($sign);
        
        $shopName = $this->getName();
        $itemName = $data['itemName'];
        $price = $this->getItemPrice($itemName);
        $stock = $this->getStockItem($itemName);
        $stoc = $stock > 99 ? "99+" : $stock;
        
        $st = new SignText([
            "[$shopName]",
            "$itemName",
            "$price",
            "Stock: $stock"
        ]);
        $world->setBlock($pos, $sign->setText($st));
    }
    
    public function getSigns() {
        $signs = $this->loadData()->get("signs");
        $tiles = [];
        foreach ($signs as $sign) {
            $world = Main::getInstance()->getServer()->getWorldManager()->getWorld($sign['worldId']);
            $pos = new Position($sign['posX'], $sign['posY'], $sign['posZ'], $world);
            $tile = $world->getBlock($pos);
            if ($tile instanceof BaseSign) {
                $tiles[] = $tile;
            }
        }
        return $tiles;
    }
    
    public function removeSign(BaseSign $tile) {
        if ($this->isSign($tile)) {
            $config = $this->loadData();
            $signs = $config->get("signs");
            foreach ($signs as $i => $sign) {
                $world = Main::getInstance()->getServer()->getWorldManager()->getWorld($sign["worldId"]);
                $pos = new Position($sign["posX"], $sign["posY"], $sign["posZ"], $world);
                if ($pos->equals($tile->getPosition())) {
                    unset($signs[$i]);
                }
            }
            $config->set("signs", $signs);
            $config->save();
            return true;
        }
        return false;
    }
    
}