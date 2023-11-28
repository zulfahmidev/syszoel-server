<?php 

namespace SysCore\PData\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\console\ConsoleCommandSender;
use SysCore\PData\PData;
use SysCore\Utils\SText;

class UpdateData extends Command {
    
    public function __construct() {
        parent::__construct("update-data", "Update user data.", "update-data <xid>", ["ud"]);
        $this->setPermission("pocketmine.group.operator");
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (isset($args[0])) {
            $xid = $args[0];
            PData::update($xid);
            $sender->sendMessage(SText::formatServerMessage("Updated.", SText::FSM_TYPE_SUCCESS));
        }
        $sender->sendMessage(SText::formatServerMessage($this->getUsage(), SText::FSM_TYPE_ERROR));
    }
    
}