<?php 

namespace SysCore\PShop\UIs;

use SysCore\Utils\SForm\SForm;
use SysCore\PGadget\PGadget;
use pocketmine\world\Position;
use SysCore\Utils\SForm\SimpleForm;
use pocketmine\player\Player;
use SysCore\PShop\PShopEvents;
use SysCore\Utils\SText;

class Storages extends SForm {
    
    private $data = [];
    private $gadget;
    private $shop;
    
    public function __construct(PGadget $gadget, array $data = []) {
        $this->gadget = $gadget;
        $this->data = $data;
        $this->shop = $data['shop'];
    }
    
    public function getStorages() {
        return $this->shop->getStorages();
    }
    
    public function open() {
        $form = new SimpleForm(function(Player $player, $data) {
            if (is_null($data)) return false;
            if ($data == 0) return $this->gadget->back();
            if ($data == 1) {
                PShopEvents::$actions[$player->getXuid()] = [
                    "name" => "set-storage",
                    "expired" => time() + 60,
                    "data" => [
                        "shop" => $this->shop,
                        "ui" => $this
                    ]
                ];
                $this->gadget->getPlayer()->sendMessage(SText::formatServerMessage("Action set storage is ready!"));
                return true;
            }
            $this->data['storage'] = $this->getStorages()[$data-2];
            $this->gadget->open('pshop-detailstorage', $this->data);
        });
        $form->setTitle($this->formatTitle("PSHOP - STORAGES"));
        $form->addButton($this->formatButton("BACK"));
        $form->addButton($this->formatButton("ADD STORAGE"));
        foreach ($this->getStorages() as $v) {
            $pos = $v->getPosition();
            $form->addButton($this->formatButton($pos->getX() ."-". $pos->getY() ."-". $pos->getZ()));
        }
        $form->sendToPlayer($this->gadget->getPlayer());
    }
    
}