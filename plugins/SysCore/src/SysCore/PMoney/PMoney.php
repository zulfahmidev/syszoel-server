<?php 

namespace SysCore\PMoney;

use SysCore\PData\PData;
use SysCore\Utils\API;

class PMoney {
    
    public static function getMoney(string $xid) {
        return PData::get($xid, "money");
    }
    
    public static function setMoney(string $xid, int $amount) {
        $res = API::post("player/$xid/money/set", [
            "amount" => $amount
        ]);
        if (isset($res['status'])) {
            if ($res['statis'] == 200) {
                return true;
            }
        }
        return false;
    }
    
}