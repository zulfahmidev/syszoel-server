<?php 

namespace SysCore\PData\UIs;

use SysCore\Utils\SForm\SForm;
use SysCore\PGadget\PGadget;
use SysCore\Utils\SForm\SimpleForm;
use pocketmine\player\Player;
use SysCore\Utils\SForm\ModalForm;
use SysCore\PShop\Shop;
use SysCore\PMoney\PMoney;
use SysCore\PRank\PRank;
use SysCore\PData\PData;

class Profile extends SForm {
    
    private $data = [];
    private $gadget;
    
    public function __construct(PGadget $gadget, array $data = []) {
        $this->gadget = $gadget;
        $this->data = $data;
    }
    
    public function getInfo() {
        $player = $this->gadget->getPlayer();
        $pos = $player->getPosition();
        $rank = PRank::getRank($player->getXuid());
        return [
            "Name" => $player->getName(),
            "Position" => $pos->world->getDisplayName() . ", " . $pos->x . "-" . $pos->y . "-" . $pos->z,
            "Money" => PMoney::getMoney($player->getXuid()),
            "Rank" => $rank->getName(),
            "Join Time" => PData::getPlayerJoinTime($player->getXuid()) . " Minutes",
            "Clan" => "no clan",
        ];
    }
    
    public function open() {
        $form = new ModalForm(function(Player $player, $data) {
            if (is_null($data)) return false;
            if ($data) {
                return $this->gadget->back();
            }
            return true;
        });
        $form->setTitle($this->formatTitle("PSHOP | PROFILE"));
        $content = "";
        $i = 0;
        foreach ($this->getInfo() as $k => $v) {
            $content .= "[".++$i."] $k: $v\n";
        }
        $form->setContent($content);
        $form->setButton1($this->formatButton("BACK"));
        $form->setButton2($this->formatButton("CLOSE"));
        $form->sendToPlayer($this->gadget->getPlayer());
    }
    
}