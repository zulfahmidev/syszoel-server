<?php 

namespace SysCore\PShop\UIs;

use SysCore\Utils\SForm\SForm;
use SysCore\PGadget\PGadget;
use SysCore\Utils\SForm\SimpleForm;
use pocketmine\player\Player;
use SysCore\PShop\Shop;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\block\Barrel;
use pocketmine\item\ItemBlock;
use pocketmine\item\VanillaItems;
use pocketmine\block\VanillaBlocks;
use SysCore\PShop\PShopEvents;
use SysCore\Utils\SText;

class ItemMenu extends SForm {
    
    private $data = [];
    private $gadget;
    private $shop;
    private $item;
    
    public function __construct(PGadget $gadget, array $data = []) {
        $this->gadget = $gadget;
        $this->data = $data;
        $this->item = $data['item'];
        $this->shop = $data['shop'];
    }
    
    public function open() {
        $form = new SimpleForm(function(Player $player, $data) {
            if (is_null($data)) return false;
            if ($data == 1) {
                return $this->gadget->open("pshop-setprice", $this->data);
            }elseif ($data == 2) {
                PShopEvents::$actions[$player->getXuid()] = [
                    "name" => "set-sign",
                    "expired" => time() + 60,
                    "data" => [
                        "shop" => $this->shop,
                        "item" => $this->item,
                        "count" => $this->data['count'],
                        "ui" => $this
                    ]
                ];
                $this->gadget->getPlayer()->sendMessage(SText::formatServerMessage("Action set sign is ready!"));
            }
        });
        $form->setTitle($this->formatTitle("PSHOP - ITEMS"));
        $form->addButton($this->formatButton("BACK"));
        $form->addButton($this->formatButton("SET PRICE"));
        $form->addButton($this->formatButton("SET SIGN"));
        $form->sendToPlayer($this->gadget->getPlayer());
    }
    
}