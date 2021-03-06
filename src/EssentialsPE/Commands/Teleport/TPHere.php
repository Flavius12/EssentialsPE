<?php
namespace EssentialsPE\Commands\Teleport;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TPHere extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "tphere", "Teleport a player to you", "/tphere <player>", ["s"]);
        $this->setPermission("essentials.tphere");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game");
            return false;
        }
        if(count($args) !== 1){
            $sender->sendMessage(TextFormat::RED . $this->getUsage());
            return false;
        }
        $player = $this->getPlugin()->getPlayer($args[0]);
        if(!$player){
            $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
            return false;
        }
        $player->teleport($sender->getPosition());
        $player->sendMessage(TextFormat::YELLOW . "Teleporting to " . $sender->getDisplayName() . "...");
        $sender->sendMessage(TextFormat::YELLOW . "Teleporting " . $player->getDisplayName() . " to you...");
        return true;
    }
} 