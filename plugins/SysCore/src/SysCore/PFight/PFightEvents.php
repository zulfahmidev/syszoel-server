<?php 

namespace SysCore\PFight;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\player\Player;

class PFightEvents implements Listener {
    
    public function onDeath(PlayerDeathEvent $e) {
        $player = $e->getPlayer();
     
        if (($arena = PFight::getPlayerArena($player))) {
            if ($arena instanceof Arena) {
                $arena->onPlayerLost($player);
            }
        }
    }
    
    public function onChat(PlayerChatEvent $e) {
        $player = $e->getPlayer();
        
        if (PFight::inArena($player)) {
            $e->cancel();
        }
    }
    
    public function onLeave(PlayerQuitEvent $e) {
        $player = $e->getPlayer();
        
        if (($arena = PFight::getPlayerArena($player))) {
            if ($arena instanceof Arena) {
                $arena->onPlayerLost($player);
            }
        }
    }
    
    public function onDestroy(BlockBreakEvent $e) {
        $player = $e->getPlayer();
        
        if (($arena = PFight::getPlayerArena($player))) {
            if ($arena instanceof Arena) {
                if ($arena->isStarted) {
                    $e->cancel();
                }
            }
        }
    }
    
    public function onInteract(PlayerInteractEvent $e) {
        $player = $e->getPlayer();
        
        if (($arena = PFight::getPlayerArena($player))) {
            if ($arena instanceof Arena) {
                if ($arena->isStarted) {
                    $e->cancel();
                }
            }
        }
    }
    
    public function onDemage(EntityDamageByEntityEvent $e) {
        $damager = $e->getDamager();
        $player = $e->getEntity();
        if ($damager instanceof Player) {
            if (($pArena = PFight::getPlayerArena($player))) {
                if (($dArena = PFight::getPlayerArena($damager))) {
                    if ($dArena->getId() == $pArena->getId()) {
                        return true;
                    }
                }
                $e->cancel();
            }
        }
    }
    
}