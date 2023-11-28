<?php 

namespace SysCore\PClan;

use pocketmine\player\Player;

class PClan {
    
    public static const ROLE_LEADER = 1;
    public static const ROLE_CO_LEADER = 2;
    public static const ROLE_MEMBER = 3;
    
    public static function getPlayerClan(Player $player) {}
    
    public static function getClan($clanId) {}
    
    public static function createClan(Player $player, $data) {}
    
    public static function deleteClan($clanId) {}
    
}