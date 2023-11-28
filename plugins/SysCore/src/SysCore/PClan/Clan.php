<?php

namespace SysCore\PClan;

use pocketmine\player\Player;

class Clan {
    
    private $clanId;
    private $data;
    
    public function __construct($clanId) {
        $this->clanId = $clanId;
        $this->data = $this->initData();
    }
    
    public function initData() {
        return [
            "id" => 1,
            "name" => "clan 1",
            "code-name" => "cl1",
            "founder_xid" => "1231312312312",
            "members" => [
                [
                    "id" => 1,
                    "player_xid" => "1231312312312",
                    "clan_role_id" => 1
                ]
            ]
        ];
    }
    
    public function getId() {
        return $this->clanId;
    }
    
    public function getName() {
        return $this->data['name'];
    }
    
    public function getCodeName() {
        return $this->data['code-name'];
    }
    
    public function getFounderXid() {
        return $this->data['founder_xid'];
    }
    
    public function isLeader($xid) {
        foreach ($this->data['members'] as $m) {
            if ($m['clan_role_id'] == PClan::ROLE_LEADER) {
                if ($m['player_xid'] == $xid) {
                    return true;
                }
            }
        }
        return false;
    }
    
    public function getMembersData() {
        return array_map(function($v) {
            return [
                "player_xid" => $v['player_xid'],
                "clan_role_id" => $v['clan_role_id'],
            ];
        }, $this->data['members']);
    }
    
    public function isMember($xid) {
        foreach ($this->data['members'] as $v) {
            if ($v['player_xid'] == $xid) {
                return true;
            }
        }
        return false;
    }
    
    public function addMember($xid) {
        if (!$this->isMember($xid)) {
            
            // Request Tambah Anggota
            
            // Kembalikan data member
            return null;
        }
        return false;
    }
    
    public function kickMember($xid) {
        
        if (!$this->isLeader($xid)) {
            if ($this->isMember($xid)) {
                
                // Request Hapus Anggota
                
                return true;
            }   
        }
        return false;
    }
    
    public function promoteMember($xid, $role_id) {
        
    }
    
    public function leaveClan($xid) {
        if ($this->isMember($xid)) {
            
            // Request Hapus Anggota
            
            
            // If Leader, Promote Anyone to be Leader
            if ($this->isLeader($xid)) {
                $coleaders = [];
                foreach ($this->data['members'] as $m) {
                    if ($m['clan_role_id'] == PClan::ROLE_CO_LEADER) {
                        $coleaders[] = $m;
                    }
                }
                
                if (count($coleaders) > 0) {
                    $this->promoteMember($coleaders[0]['player_xid'], PClan::ROLE_LEADER);
                }else {
                    $this->promoteMember($this->data['members'][0]['player_xid'], PClan::ROLE_LEADER);
                }
            }
            
            return true;
        }
        return false;   
    }
    
}