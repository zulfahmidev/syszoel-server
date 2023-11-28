<?php 

namespace SysCore\PChat;

use pocketmine\player\chat\ChatFormatter;
use SysCore\Utils\SText;
use pocketmine\lang\Translatable;

class PChatFormat implements ChatFormatter {
    
    private $xid;
    
    public function __construct($xid) {
        $this->xid = $xid;
    }
    
    public function format(string $username, string $message) : Translatable|string {
        return SText::formatPlayerMessage($this->xid, $message);
    }
}