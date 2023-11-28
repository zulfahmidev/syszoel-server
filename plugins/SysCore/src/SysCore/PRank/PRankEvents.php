<?php 

namespace SysCore\PRank;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class PRankEvents implements Listener {
    
    public function onJoin(PlayerJoinEvent $e) {
        $player = $e->getPlayer();
        PRank::updateDisplayName($player);
    }
    
}