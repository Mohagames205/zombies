<?php

namespace mohagames\zombies\Tasks;

use mohagames\zombies\Main;
use mohagames\zombies\ZombieType;
use pocketmine\scheduler\Task;

class ZombieSpawnTask extends Task{
    public $main;

    public function __construct()
    {
        $this->main = Main::getInstance();
    }

    public function onRun(int $currentTick)
    {
        $players = $this->main->getServer()->getOnlinePlayers();
        foreach($players as $player){
            $rand = mt_rand(-99, 99);
            $pos = $player->asVector3()->add($rand, 0, $rand);
            $this->main->spawnEntity($pos, $player->getLevel());
        }
    }
}