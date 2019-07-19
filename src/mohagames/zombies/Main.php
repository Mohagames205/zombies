<?php

namespace mohagames\zombies;

use mohagames\zombies\tasks\ZombieDamageTask;
use mohagames\zombies\tasks\ZombieDespawnTask;
use mohagames\zombies\tasks\ZombieMoveTask;
use mohagames\zombies\tasks\ZombieSpawnTask;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\entity\EntityIds;
use pocketmine\entity\Zombie;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\level\Level;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\network\mcpe\protocol\InteractPacket;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\math\Vector3;
use pocketmine\scheduler\Task;

class Main extends PluginBase implements Listener{

    protected static $instance;


    public function onEnable()
    {
        Main::$instance = $this;
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getScheduler()->scheduleRepeatingTask(new ZombieMoveTask(), 1);
        $this->getScheduler()->scheduleRepeatingTask(new ZombieDespawnTask(), 20);
        $this->getScheduler()->scheduleRepeatingTask(new ZombieSpawnTask(), 2000);
        $this->getScheduler()->scheduleRepeatingTask(new ZombieDamageTask(), 20);


    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        switch($command->getName()){
            case "zombie":
                $this->spawnEntity($sender->asVector3(), $sender->getLevel());
                return true;
            case "destroy":
                $this->destroyAllEntities($sender->getLevel());
                return true;
            default:
                return false;
        }
    }


    public function onEntitykill(EntityDeathEvent $e){
        $e->setDrops(array(Item::get(ItemIds::AIR)));

    }

    /**
     * @return mixed
     */
    public static function getInstance(){
        return Main::$instance;
    }

    public function spawnEntity(Vector3 $coords, Level $level){
        $nbt = Entity::createBaseNBT($coords);
        $entity = Entity::createEntity("Zombie", $level, $nbt);
        $entity->setNameTag("§4Zombie");
        $entity->setCanClimbWalls();
        $entity->spawnToAll();

    }

    public function destroyAllEntities(Level $level){
        $entities = $level->getEntities();
        foreach($entities as $entity){
            if($entity instanceof Zombie){
                $entity->flagForDespawn();
            }
        }
    }








}