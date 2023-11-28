<?php 

namespace SysCore\PShop\UIs;

use SysCore\Utils\SForm\SForm;
use SysCore\PGadget\PGadget;
use SysCore\Utils\SForm\SimpleForm;
use pocketmine\player\Player;
use SysCore\Utils\SForm\ModalForm;
use SysCore\PShop\Shop;
use SysCore\Utils\SForm\CustomForm;

class SetPrice extends SForm {
    
    private $data = [];
    private $gadget;
    private $shop;
    private $item;
    
    public function __construct(PGadget $gadget, array $data = []) {
        $this->gadget = $gadget;
        $this->shop = $data['shop'];
        $this->item = $data['item'];
        $this->data = $data;
    }
    
    public function getDefaultPrice() {
        return $this->shop->getItemPrice($this->item->getName());
    }
    
    public function open() {
        $form = new CustomForm(function(Player $player, $data) {
            if (is_null($data)) return false;
            $val = $this->validate([
                "price" => $data[0],
            ], [
                "price" => "required|numeric",
            ]);
            if (!empty($val)) {
                $this->data['errors'] = $val;
                return $this->open();
            }
            $this->shop->setItemPrice($this->item->getName(), (int)$data[0]);
            $this->gadget->back();
        });
        
        $form->setTitle($this->formatTitle("PSHOP | ".$this->item->getName()));
        $form->addInput("Price", "Type here...", $this->getDefaultPrice());
        $form->addLabel($this->getError("price", $this->data));
        $form->sendToPlayer($this->gadget->getPlayer());
    }
    
}