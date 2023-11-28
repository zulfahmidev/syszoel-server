<?php 

namespace SysCore\PRank;

use pocketmine\player\Player;
use pocketmine\permission\Permission;

class Rank {
    
    private $data;
    private $permissions = [];
    
    public function __construct($data) {
        $this->data = $data;
        foreach ($data['permissions'] as $pname) {
            $this->permissions[] = new Permission($pname);
        }
    }
    
    public function getMinTime() {
        return (int) $this->data['time'];
    }
    
    public function getName() {
        return $this->data["name"];
    }
    
    public function getDisplayName() {
        return $this->data["display-name"];
    }
    
    public function getPermissions() {
        return $this->permissions;
    }
    
}