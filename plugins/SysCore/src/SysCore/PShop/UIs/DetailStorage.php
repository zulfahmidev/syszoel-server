<?php 

namespace SysCore\PShop\UIs;

use SysCore\Utils\SForm\SForm;
use SysCore\PGadget\PGadget;
use SysCore\Utils\SForm\SimpleForm;
use pocketmine\player\Player;
use SysCore\PShop\Shop;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemBlock;
use pocketmine\item\VanillaItems;
use pocketmine\block\VanillaBlocks;
use SysCore\PShop\PShopEvents;
use SysCore\Utils\SText;
use pocketmine\block\tile\Barrel;
use SysCore\Utils\SForm\ModalForm;
use pocketmine\world\Position;

class DetailStorage extends SForm {
    
    private $data = [];
    private $gadget;
    private $storage;
    private $shop;
    
    public function __construct(PGadget $gadget, array $data = []) {
        $this->gadget = $gadget;
        $this->data = $data;
        $this->storage = $data['storage'];
        $this->shop = $data['shop'];
    }
    
    public function getInfo() {
        $pos = $this->storage->getPosition();
        return [
            "World" => $pos->world->getDisplayName(),
            "Position X" => $pos->x,
            "Position Y" => $pos->y,
            "Position Z" => $pos->z
        ];
    }
    
    public function open() {
        $form = new ModalForm(function(Player $player, $data) {
            if (is_null($data)) return false;
            if ($data) {
                return $this->gadget->back();
            }
            $shop = $this->shop;
            $shop->removeStorage($this->storage);
            $this->gadget->getPlayer()->sendMessage(SText::formatServerMessage("Storage succesfully removed"));
            return $this->gadget->back();
        });
            $form->setTitle($this->formatTitle("PSHOP | PROFILE"));
            $content = "";
            $i = 0;
            foreach ($this->getInfo() as $k => $v) {
                $content .= "[".++$i."] $k: $v\n";
            }
            $form->setContent($content);
            $form->setButton1($this->formatButton("BACK"));
            $form->setButton2($this->formatButton("REMOVE"));
            $form->sendToPlayer($this->gadget->getPlayer());
    }
    
}