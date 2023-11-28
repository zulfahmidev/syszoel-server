<?php 

namespace SysCore\PGadget;

use pocketmine\player\Player;
use SysCore\Utils\SForm\SForm;
use SysCore\Utils\SForm\SimpleForm;
use SysCore\PShop\UIs\PShopApp;

class MainMenu extends SForm {
    
    private $data = [];
    private $gadget;
    
    public function __construct(PGadget $gadget, array $data = []) {
        $this->gadget = $gadget;
        $this->data = $data;
    }
    
    public function getMenu() {
        return [
            [
                "title" => "PROFIL",
                "formName" => "profile",
                "permission" => "syscore.pgadget.profile",
            ],
            [
                "title" => "PSHOP",
                "formName" => "pshop-main",
                "permission" => "syscore.pgadget.pshop",
            ],
            [
                "title" => "PVP",
                "formName" => "pvp-main",
                "permission" => "syscore.pgadget.pvp",
            ],
        ];
    }
    
    public function open() {
        $form = new SimpleForm(function(Player $player, $data) {
            if (is_null($data)) return false;
            $menu = $this->getMenu()[$data]["formName"];
            return $this->gadget->open($menu);
        });
        $form->setTitle($this->formatTitle("MAIN MENU"));
        foreach ($this->getMenu() as $menu) {
            if ($this->gadget->getPlayer()->hasPermission($menu['permission'])) {
                $form->addButton($this->formatButton($menu['title']));
            }
        }
        $form->sendToPlayer($this->gadget->getPlayer());
    }
    
}