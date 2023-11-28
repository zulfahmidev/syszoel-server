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

class Items extends SForm {
    
    private $data = [];
    private $gadget;
    private $shop;
    
    public function __construct(PGadget $gadget, array $data = []) {
        $this->gadget = $gadget;
        $this->data = $data;
        $this->shop = $data['shop'];
    }
    
    public function getItemById($id) {
        $blocks = VanillaBlocks::getAll();
        $items = VanillaItems::getAll();
        foreach ($blocks as $block) {
            if ($block->getTypeId() == $id) return $block;
        }
        foreach ($items as $item) {
            if ($item->getTypeId() == $id) return $item;
        }
        return null;
    }
    
    public function getItems() {
        $items = $this->shop->getItems();
        $rows = [];
        foreach ($items as $name => $v) {
            $item = $v["item"];
            $count = $v["count"];
            $rows[] = [
                "item" => $item,
                "count" => $count,
            ];
        }
        return $rows;
    }
    
    public function open() {
        $form = new SimpleForm(function(Player $player, $data) {
            if (is_null($data)) return false;
            if ($data == 0) return $this->gadget->back();
            $this->data['item'] = $this->getItems()[$data-1]['item'];
            $this->data['count'] = $this->getItems()[$data-1]['count'];
            $this->gadget->open('pshop-itemmenu', $this->data);
        });
        $form->setTitle($this->formatTitle("PSHOP - ITEMS"));
        $form->addButton($this->formatButton("BACK"));
        foreach ($this->getItems() as $v) {
            $form->addButton($this->formatButton($v["item"]->getName() . " : " . $v['count']));
        }
        $form->sendToPlayer($this->gadget->getPlayer());
    }
    
}