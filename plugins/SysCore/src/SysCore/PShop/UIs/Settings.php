<?php 

namespace SysCore\PShop\UIs;

use SysCore\Utils\SForm\SForm;
use SysCore\PGadget\PGadget;
use SysCore\Utils\SForm\SimpleForm;
use pocketmine\player\Player;
use SysCore\Utils\SForm\ModalForm;
use SysCore\PShop\Shop;
use SysCore\Utils\SForm\CustomForm;

class Settings extends SForm {
    
    private $data = [];
    private $gadget;
    private $shop;
    
    public function __construct(PGadget $gadget, array $data = []) {
        $this->gadget = $gadget;
        $this->data = $data;
        $this->shop = $data['shop'];
    }
    
    public function open() {
        $form = new CustomForm(function(Player $player, $data) {
            if (is_null($data)) return false;
            
            $val = $this->validate([
                "name" => $data[0],
            ], [
                "name" => "required",
            ]);
            if (!empty($val)) {
                $this->data['errors'] = $val;
                return $this->open();
            }
            if ($this->changeName($data[0])) {
                return $this->gadget->back();
            }else {
                return $this->open();
            }
        });
        
        $form->setTitle($this->formatTitle("PSHOP | SETTINGS"));
        $form->addInput("Name", "Type here...", $this->shop->getName());
        $form->addLabel($this->getError("name", $this->data));
        $form->sendToPlayer($this->gadget->getPlayer());
    }
    
    public function changeName($name) {
        return $this->shop->setName($name);
    }
    
}