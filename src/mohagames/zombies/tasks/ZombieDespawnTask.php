<?php


namespace mohagames\zombies\Tasks;

use mohagames\zombies\Main;
use pocketmine\entity\Entity;
use pocketmine\entity\Zombie;
use pocketmine\scheduler\Task;

class ZombieDespawnTask extends Task
{

    public $entity;
    public $main;
    public $despawn_distance;
    public $player_nearby;

    public function __construct()
    {

        $this->main = Main::getInstance();
        //TODO: CONFIG
        $this->despawn_distance = 100;
    }

    public function onRun(int $currentTick)
    {
        $levels = $this->main->getServer()->getLevels();
        foreach($levels as $level){
            $entities = $level->getEntities();
            foreach($entities as $entity){
                if(!$this->entityNearby($entity)){
                    $entity->flagForDespawn();
                }
            }
        }

    }

    //TODO: Dit stuk garbage code fixen

    public function entityNearby(Entity $entity){
        $players = $this->main->getServer()->getOnlinePlayers();
        $check = false;
        foreach($players as $player) {
            $dist = $player->distance($entity);
            if ($dist <= $this->despawn_distance && $entity->getLevel() == $player->getLevel()) {
                $check = true;
                break;
            }
        }
        #var_dump($check);
        return $check;
    }

}