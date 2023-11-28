<?php 

namespace SysCore\PGadget\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use SysCore\Utils\SText;
use SysCore\PGadget\PGadget;

class Gadget extends Command {
    
    public function __construct() {
        parent::__construct("gadget", "Open player gadget.", '/gadget', ['g']);
        $this->setPermission("syscore.pgadget.cmd.gadget");;
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender instanceof Player) {
            $sender->sendMessage(SText::formatServerMessage("sender-not-player"));
        }
        
        $gadget = new PGadget($sender);
        $gadget->open();
    }

    
}