<?php 

namespace SysCore\PChat;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

class PChatEvents implements Listener {
    
    public function onMessage(PlayerChatEvent $e) {
        $player = $e->getPlayer();
        $message = $e->getMessage();
        $e->setFormatter(new PChatFormat($player->getXuid()));
    }
        
}