<?php

namespace mohagames\zombies\Tasks;


use mohagames\zombies\Main;
use pocketmine\entity\Entity;
use pocketmine\entity\Zombie;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\item\Bow;
use pocketmine\math\Vector3;
use pocketmine\scheduler\Task;

class ZombieDamageTask extends Task{
    public $main;

    public function __construct()
    {
        $this->main = Main::getInstance();
    }

    public function onRun(int $currentTick)
    {
        $players = $this->main->getServer()->getOnlinePlayers();

        foreach ($players as $player) {
            //TODO DEBUG var_dump($player->getLevel()->getCollisionBlocks($player->getBoundingBox()));
            $entities = $player->getLevel()->getEntities();
            foreach ($entities as $entity) {
                if($entity instanceof Zombie) {
                    $dist = $player->distance($entity);
                    if ($dist < 1 && $entity->isAlive()) {
                        $event = new EntityDamageEvent($player, EntityDamageEvent::CAUSE_ENTITY_ATTACK, 3);
                        //TODO: IDK WERKT NIET GOED $player->knockBack($entity, 1, $player->getX(), $player->getZ(), 0.3);
                        $player->attack($event);
                    }

                }
            }
        }

    }
}