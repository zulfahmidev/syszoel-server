<?php 

namespace SysCore\PCrate;

use pocketmine\block\Chest;
use pocketmine\world\Position;
use SysCore\Main;
use pocketmine\player\Player;
use pocketmine\item\Item;
use pocketmine\network\mcpe\InventoryManager;
use pocketmine\item\ItemBlock;
use pocketmine\item\VanillaItems;
use pocketmine\block\inventory\ChestInventory;
use pocketmine\block\VanillaBlocks;

class PCrate {
    
    private static $crates = [];
    
    public static function initCrates() {
        self::$crates = [
            [
                "name" => "Crate 1",
                "position" => [0,0,0,'world'],
                "rewards" => [
                    [
                        "item-name" => "stone",
                        "item-limit" => 64,
                    ]
                ]
            ]
        ];
    }
    
    public static function getCrates() {
        return self::getCrates();
    }
    
    public static function isCrate($block) {
        return !is_null(self::getCrate($block));
    }
    
    public static function openCrate(Player $player, $block) {
        if (($crate = self::getCrate($block))) {
            if (true) { // Check have key or not
                
                $rewards = self::getRewards($crate);
                
                $p = $player->getPosition();
                $p->y = 200;
                while (!is_null($p->world->getTile($p))) {
                    $p->y++;
                }
                $player->getWorld()->setBlockAt($p->x,$p->y,$p->z, VanillaBlocks::CHEST());
                $inv = new ChestInventory($p);
                
                foreach (VanillaItems::getAll() as $item) {
                    foreach ($rewards as $r) {
                        if (strtolower($r['item-name']) == strtolower($item->getName())) {
                            $item->setCount($r['item-count']);
                            $inv->addItem($item);
                        }
                    }
                }
                
                $player->setCurrentWindow($inv);
                return true;
            }
        }
        return false;
    }
    
    private static function getRewards($crate) {
        $rewards = [];
        $times = rand(1, 5);
        for ($i=0;$i<$times;$i++) {
            $reward = $crate['rewards'][rand(0, count($crate['rewards']))];
            $rewards[] = [
                "item-name" => $reward['item-name'],
                "item-count" => rand(1, $reward['item-limit']),
            ];
        }
        return $rewards;
    }
    
    public static function getCrate($block) {
        foreach (self::$crates as $crate) {
            $pos = $crate['position'];
            $world = Main::getInstance()->getServer()->getWorldManager()->getWorldByName($pos[3]);
            if ($block->getPosition()->equals(new Position($pos[0], $pos[1], $pos[2], $world))) {
                return $crate;
            }
        }
        return null;
    }
    
}