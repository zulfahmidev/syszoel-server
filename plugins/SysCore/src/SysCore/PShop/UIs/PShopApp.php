<?php 

namespace SysCore\PShop\UIs;

use SysCore\Utils\SForm\SForm;
use SysCore\PGadget\PGadget;
use SysCore\Utils\SForm\SimpleForm;
use pocketmine\player\Player;
use SysCore\PShop\Shop;

class PShopApp extends SForm {
    
    private $data = [];
    private $gadget;
    
    public function __construct(PGadget $gadget, array $data = []) {
        $this->gadget = $gadget;
        $this->data = $data;
    }
    
    public function getMenu() {
        return [
            [
                "title" => "PROFILE",
                "formName" => "pshop-profile"
            ],
//             [
//                 "title" => "HELPERS",
//                 "formName" => "pshop-profile"
//             ],
            [
                "title" => "ITEMS",
                "formName" => "pshop-items"
            ],
            [
                "title" => "STORAGES",
                "formName" => "pshop-storages"
            ],
            [
                "title" => "SETTINGS",
                "formName" => "pshop-settings"
            ],
        ];
    }
    
    public function open() {
        $form = new SimpleForm(function(Player $player, $data) {
            if (is_null($data)) return false;
            if ($data == 0) return $this->gadget->back();
            $menu = $this->getMenu()[$data-1]["formName"];
            $shop = new Shop($this->gadget->getPlayer()->getXuid());
            return $this->gadget->open($menu, ["shop" => $shop]);
        });
        $form->setTitle($this->formatTitle("PSHOP"));
        $form->addButton($this->formatButton("BACK"));
        foreach ($this->getMenu() as $menu) {
            $form->addButton($this->formatButton($menu['title']));
        }
        $form->sendToPlayer($this->gadget->getPlayer());
    }
    
}