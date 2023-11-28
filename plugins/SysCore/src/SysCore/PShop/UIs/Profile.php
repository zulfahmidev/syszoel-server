<?php 

namespace SysCore\PShop\UIs;

use SysCore\Utils\SForm\SForm;
use SysCore\PGadget\PGadget;
use SysCore\Utils\SForm\SimpleForm;
use pocketmine\player\Player;
use SysCore\Utils\SForm\ModalForm;
use SysCore\PShop\Shop;

class Profile extends SForm {
    
    private $data = [];
    private $gadget;
    private $shop;
    
    public function __construct(PGadget $gadget, array $data = []) {
        $this->gadget = $gadget;
        $this->data = $data;
        $this->shop = $data['shop'];
    }
    
    public function getInfo() {
        $shop = $this->shop;
        return [
            "Name" => $shop->getName(),
            "Owner" => $shop->getOwnerName(),
//             "Helpers" => count($shop->getHelpers()),
            "Storages" => count($shop->getStorages())
        ];
    }
    
    public function open() {
        $form = new ModalForm(function(Player $player, $data) {
            if (is_null($data)) return false;
            if ($data) {
                return $this->gadget->back();
            }
            return $this->gadget->open("pshop-settings", [
                "shop" => $this->shop
            ]);
        });
        $form->setTitle($this->formatTitle("PSHOP | PROFILE"));
        $content = "";
        $i = 0;
        foreach ($this->getInfo() as $k => $v) {
            $content .= "[".++$i."] $k: $v\n";
        }
        $form->setContent($content);
        $form->setButton1($this->formatButton("BACK"));
        $form->setButton2($this->formatButton("SETTINGS"));
        $form->sendToPlayer($this->gadget->getPlayer());
    }
    
}