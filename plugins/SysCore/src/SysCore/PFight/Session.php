<?php

namespace SysCore\PFight;

use pocketmine\scheduler\Task;
use pocketmine\player\Player;

class Session extends Task {
    
    private $arena;
    
    public function __construct(Arena $arena) {
        $this->arena = $arena;
    }
    
    public function onRun(): void {
        $arena = $this->arena;
        $arena->sessionTime = time()-$arena->startedAt;        
        foreach ($arena->matchings as $player) {
            if ($player instanceof Player) {
                if ($arena->sessionTime <= $arena->countdown) {
                    $countdown = $arena->countdown - $arena->sessionTime;
                    
                    // Pesan Hitung Mundur
                    $player->sendTitle("Get Ready!!!", "The game will start in $countdown seconds.");
                    
                } else {
                    if (!$arena->isStarted) {
                        $arena->isStarted = true;
                        $arena->setupFightPlayers();
                        
                        // Pesan Mulai
                        $player->sendMessage("Let's Fight!!!");
                    }else {
                        
                        $player->sendTip((((int) $arena->data['time-limit'] * 60) - $arena->sessionTime) . " seconds.");
                    }
                }
            }
        }
        
        if (floor($arena->sessionTime/60) >= $arena->data['time-limit']) {
            $arena->endGame();
        }
    }
    
}