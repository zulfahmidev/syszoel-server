<?php 

namespace SysCore\PPerms;

use pocketmine\permission\PermissionManager;
use pocketmine\permission\Permission;
use syscore\Main;
use pocketmine\permission\PermissibleInternal;

class PPerms {
    
    public static function loadPermissions() {
        return [
            "syscore.pdata.cmd.update-data",
            "syscore.pgadget.cmd.gadget",
            "syscore.pgadget.profile",
            "syscore.pgadget.pshop",
        ];
    }
    
    public static function registerPermissions() {
        $permissions = self::loadPermissions();
        foreach ($permissions as $name) {
            $permission = new Permission($name);
            $perm = PermissionManager::getInstance()->addPermission($permission);
        }
    }
    
}