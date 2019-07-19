<?php

namespace mohagames\zombies\tasks;

use mohagames\zombies\Main;
use pocketmine\entity\Entity;
use pocketmine\entity\Zombie;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\item\Bow;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\scheduler\Task;

class ZombieMoveTask extends Task{

    public $entity;
    public $main;

    public function __construct()
    {
        $this->main = Main::getInstance();
    }

    public function onRun(int $currentTick){
        $players = $this->main->getServer()->getOnlinePlayers();
                foreach ($players as $player) {
                    $entities = $player->getLevel()->getEntities();
                    foreach ($entities as $entity) {
                        if($entity instanceof Zombie) {
                            $dist = $player->distance($entity);
                            if ($dist <= 15 && $entity->getLevel() === $player->getLevel() && $player->getGamemode() != Player::CREATIVE) {
                                $level = $entity->getLevel();
                                var_dump($level->getCollisionBlocks($entity->getBoundingBox()));


                                //dit is het getal waarmee de $x en $y moeten gedeeld worden. Zoals je ziet wordt dit hier bepaald door de afstand tussen de speler en de entity.
                                $divider = 4;
                                if($dist <= 10){
                                    $divider = 8;
                                }
                                if($dist <= 12){
                                    $divider = 18;
                                }
                                if($dist <= 3){
                                    $divider = 6;
                                }
                                //var_dump($dist);

                                //Dit spreekt voor zich denk ik. $entity->setMotion() duwt een bepaalde entity x blocks naar voor.
                                $x = $player->getX() - $entity->getX();
                                $z = $player->getZ() - $entity->getZ();
                                $vector3 = new Vector3($x, 0, $z);
                                $entity->setMotion($vector3->divide($divider));
                                $entity->lookAt($player->asVector3());
                            }

                        }
                }
            }
        }

}