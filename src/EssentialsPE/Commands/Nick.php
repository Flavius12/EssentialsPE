<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Nick extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "nick", "Change your in-game name", "/nick <new nick|off> [player]", ["nickname"]);
        $this->setPermission("essentials.nick");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        switch(count($args)){
            case 1:
                if(!$sender instanceof Player){
                    $sender->sendMessage(TextFormat::RED . "Usage: /nick <new nick|off> <player>");
                    return false;
                }
                $nickname = $args[0];
                $nickname === "off" ? $this->getPlugin()->removeNick($sender, true) : $this->getPlugin()->setNick($sender, $nickname, true);
                $sender->sendMessage(TextFormat::GREEN . "Your nick " . ($nickname === "off" ? "has been removed" : "is now " . $nickname));
                break;
            case 2:
                if(!$sender->hasPermission("essentials.nick.other")){
                    $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                    return false;
                }
                $player = $this->getPlugin()->getPlayer($args[1]);
                if(!$player){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                    return false;
                }
                $nickname = $args[0];
                $nickname === "off" ? $this->getPlugin()->removeNick($player, true) : $this->getPlugin()->setNick($player, $nickname, true);
                $sender->sendMessage(TextFormat::GREEN . $player->getName() . (substr($player->getName(), -1, 1) === "s" ? "'" : "'s") . " nick " . ($nickname === "off" ? "has been removed" : "is now " . $nickname));
                $player->sendMessage(TextFormat::GREEN . "Your nick " . ($nickname === "off" ? "has been removed" : "is now " . $nickname));
                break;
            default:
                $sender->sendMessage(TextFormat::RED . $sender instanceof Player ? $this->getUsage() : "Usage: /nick <new nick|off> <player>");
                return false;
                break;
        }
        return true;
    }
}
