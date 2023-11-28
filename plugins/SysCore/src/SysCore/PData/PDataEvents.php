<?php 

namespace SysCore\PData;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use SysCore\Utils\SText;
use pocketmine\event\player\PlayerQuitEvent;
use syscore\Main;
use SysCore\PPerms\PPerms;
use pocketmine\permission\PermissionManager;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerInteractEvent;

class PDataEvents implements Listener {
    
    public function onJoin(PlayerJoinEvent $e) {
        $e->setJoinMessage("");
        $player = $e->getPlayer();
        if (PData::init($player)) {
            if (Main::getInstance()->getServer()->isOp($player->getName())) {
                foreach (PPerms::loadPermissions() as $name) {
                    $player->addAttachment(Main::getInstance(), $name, true);
                }
         
            }
            
        }
    }
    
    public function onQuit(PlayerQuitEvent $e) {
        $player = $e->getPlayer();
        PData::close($player->getXuid());
        $e->setQuitMessage("");
    }
    
//     public function onPlayerInteract(PlayerInteractEvent $e) {
//         $e->cancel();
//         $item = $e->getItem();
//         $player = $e->getPlayer();
//         switch (strtolower($item->getName())) {
//             case "ice":
//                 $player->sendActionBarMessage("Hello Zul");
//                 break;
//             case "stone":
//                 $player->sendJukeboxPopup("Hello Zul");
//                 break;
//             case "sand":
//                 $player->sendMessage("Hello Zul");
//                 break;
//             case "dirt":
//                 $player->sendPopup("Hello Zul");
//                 break;
//             case "stick":
//                 $player->sendSubTitle("Hello Zul");
//                 break;
//             case "glowstone":
//                 $player->sendTip("Hello Zul");
//                 break;
//             case "snow":
//                 $player->sendToastNotification("Malam", "Hello Zulfahmi");
//                 break;
//             case "barrel":
//                 $player->sendTitle("Malam", "Hello Zulfahmi");
//                 break;
//         }
//     }
    
}