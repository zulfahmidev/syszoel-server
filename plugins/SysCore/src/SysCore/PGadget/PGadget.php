<?php 

namespace SysCore\PGadget;

use pocketmine\player\Player;
use SysCore\Utils\SForm\SForm;

class PGadget {
    
    private $player;
    private $paths = [];
    
    public function __construct(Player $player) {
        $this->player = $player;
    }
    
    public function getPlayer() {
        return $this->player;
    }
    
    public function pushPath($form) {
        $this->paths[] = $form;
        return true;
    }
    
    public function back() {
        array_pop($this->paths);
        $form = end($this->paths);
        return $form->open();
    }
    
    public function open(string $formName = '', array $data = []) {
        $formClass = SForm::getForm($formName);
        if (!is_null($formClass)) {
            $form = new $formClass($this, $data);
            $this->pushPath($form);
            $form->open();
            return true;
        }
        return false;
    }
    
}