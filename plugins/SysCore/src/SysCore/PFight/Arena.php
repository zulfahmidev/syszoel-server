<?php

namespace SysCore\PFight;

use pocketmine\player\Player;
use pocketmine\utils\Config;
use SysCore\Main;
use pocketmine\world\Position;
use pocketmine\player\GameMode;
use SysCore\PMoney\PMoney;

class Arena {
    
    public $matchings = [];
    
    public $arenaId;
    public $data;
    public $countdown = 10;
    public $sessionTime = 0;
    public $startedAt = 0;
    public $isStarted = false;
    public $isReseted = false;
    public $session = null;
    
    public function __construct($arenaId, $data) {
        $this->arenaId = $arenaId;
        $this->data = $data;
    }
    
    public function getId() {
        return $this->data['id'];
    }
    
    public function getName() {
        return $this->data['name'];
    }
    
    public function join(Player $player) {
        if (count($this->matchings) < $this->data['max-player']) {
            
            if (!$this->inArena($player)) {
                $this->matchings[] = $player;
                
                // Pesan Berhasil Bergabung
                $player->sendMessage("Matching...");
                
                if (count($this->matchings) == $this->data['max-player']) {
                    
                    // Pesan Bersiap Untuk Teleport
                    $player->sendMessage("Prepare to teleport to the arena.");
                    
                    $this->teleportToArena();
                    
                    $this->startGame();
                    
                    return true;
                }
                
                return false;
            }
            
            // Pesan Sudah Bergabung
            $player->sendMessage("You have joined!");
            return false;
        }
        
        // Pesan sudah penuh
        $player->sendMessage("Maaf arena sudah penuh!");
    }
    
    public function leave(Player $player) {
        if ($this->inArena($player)) {
            if ($this->getPhase() > 1) {
                
                // Pesan Batal Keluar
                $player->sendMessage("You are still in the game.");
                return false;
            }
            
            foreach ($this->matchings as $i => $p) {
                if ($player->getXuid() == $p->getXuid()) {
                    unset($this->matchings[$i]);
                    $this->matchings = array_values($this->matchings);
                }
            }
            
            // Pesan Berhasil Batalkan
            $player->sendMessage("Matching is cancelled.");
            return true;
        }
        
        // Pesan Tidak Dalam Arena
        $player->sendMessage("You are not in the arena.");
        return false;
    }
    
    public function startGame() {
               
        $this->setupWaitingPlayers();

        $this->startedAt = time();
        $this->session = Main::getInstance()->getScheduler()->scheduleRepeatingTask(new Session($this), 20);
    }
    
    public function endGame() {
        $this->isStarted = false;
        $this->isReseted = true;
        
        $this->teleportToSpawn();
        $this->session->remove();
        
        $this->checkWinner();
        
        $this->resetArena();
    }
    
    public function inArena(Player $player) {
        foreach ($this->matchings as $q) {
            if ($player->getXuid() == $q->getXuid()) {
                return true;
            }
        }
        return false;
    }
    
    public function checkWinner() {
        
        if (count($this->matchings) == 1) {
            
            $player = $this->matchings[0];
            
            // Pesan Winner
            $player->sendTitle("Win!!!", "Congratulations on winning the match.");
            
            $this->sendRewards();
            
        } elseif (count($this->matchings) > 1) {
            
            foreach ($this->matchings as $player) {
                if ($player instanceof Player) {
                    
                    // Pesan Seri
                    $player->sendTitle("Draw!!!", "The match result is a draw, let's try again.");
                    
                }
            }
        }
    }
    
    public function onPlayerLost($player) {
        if ($player instanceof Player) {
            if ($player->isOnline()) {
                $position = Main::getInstance()->getServer()->getWorldManager()->getWorldByName('world')->getSafeSpawn();
                $player->teleport($position);
                
                foreach ($this->matchings as $i => $p) {
                    if ($player->getXuid() == $p->getXuid()) {
                        unset($this->matchings[$i]);
                        $this->matchings = array_values($this->matchings);
                    }
                }
                
                // Pesan Kalah
                $player->sendTitle("Lose!!!", "You lost the match, let's try again.");
                
                if (count($this->matchings) <= 0) {
                    $this->endGame();
                }
            }
        }
    }
    
    public function sendRewards() {
        $player = $this->matchings[0];
        if ($player instanceof Player) {
//             $amount = PMoney::getMoney($player->getXuid()) + (int) $this->data['reward'];
//             PMoney::setMoney($player->getXuid(), $amount);
            if ($player->isOnline()) {
                
                // Pesan Reward
                $player->sendMessage("You get ".$this->data['reward']." for your winnings");
            }
        }
    }
    
    public function getPhase() {
        if ($this->isReseted) return 3;
        if ($this->isStarted) return 2;
        if (count($this->matchings) >= 1) return 1;
        return 0;
    }
    
    public function resetArena() {
        $this->sessionTime = 0;
        $this->startedAt = 0;
        $this->isStarted = false;
        $this->session = null;
        $this->matchings = [];
        $this->isReseted = false;
    }
    
    public function setupWaitingPlayers() {
        foreach ($this->matchings as $player) {
            if ($player instanceof Player) {
                $player->setNoClientPredictions(true);
                $player->setGamemode(GameMode::SURVIVAL());
                $player->setFlying(false);
                $player->setHealth(20);
            }
        }
    }
    
    public function setupFightPlayers() {
        foreach ($this->matchings as $player) {
            if ($player instanceof Player) {
                $player->setNoClientPredictions(false);
            }
        }
    }
    
    public function teleportToArena() {
        $positions = $this->data['positions'];
        foreach ($positions as $i => $cord) {
            $player = $this->matchings[$i];
            if ($player instanceof Player) {
                $cord[3] = Main::getInstance()->getServer()->getWorldManager()->getWorldByName($cord[3]);
                $player->teleport(new Position(...$cord));
            }
        }
        return true;
    }
    
    public function teleportToSpawn() {
        $position = Main::getInstance()->getServer()->getWorldManager()->getWorldByName('world')->getSafeSpawn();
        foreach ($this->matchings as $player) {
            $player->teleport($position);
        }
        return true;
    }
    
}