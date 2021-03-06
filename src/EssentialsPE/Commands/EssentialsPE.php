<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class EssentialsPE extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "essentials", "Get current Essentials version", "/essentialspe [update]", ["essentials", "ess", "esspe"]);
        $this->setPermission("essential.essentials");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        switch(count($args)){
            case 0:
                $sender->sendMessage(TextFormat::YELLOW . "You're using " . TextFormat::AQUA . "EssentialsPE " . TextFormat::YELLOW . "v" . TextFormat::GREEN . $sender->getServer()->getPluginManager()->getPlugin("EssentialsPE")->getDescription()->getVersion());
                break;
            case 1:
            case 2:
                switch(strtolower($args[0])){
                    case "update":
                    case "u":
                        if(!$sender->hasPermission("essentials.update")){
                            $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                            return false;
                        }
                        if(isset($args[1]) && (($a = strtolower($args[1])) === "check" || $a === "c" || $a === "install" || $a === "i")){
                            $install = false;
                            if($a === "i" || $a === "install"){
                                $install = true;
                            }
                            if(!$this->getPlugin()->fetchEssentialsPEUpdate($install)) {
                                $sender->sendMessage(TextFormat::YELLOW . "The updater is already working... Please wait a few moments and try again");
                            }
                            return true;
                        }
                        $sender->sendMessage(TextFormat::YELLOW . ($sender instanceof Player ? "" : "Usage: ") . "/essentialspe update <check|install>");
                        break;
                    case "version":
                    case "v":
                    $sender->sendMessage(TextFormat::YELLOW . "You're using " . TextFormat::AQUA . "EssentialsPE " . TextFormat::YELLOW . "v" . TextFormat::GREEN . $sender->getServer()->getPluginManager()->getPlugin("EssentialsPE")->getDescription()->getVersion());
                        break;
                    default:
                        $sender->sendMessage(TextFormat::RED . ($sender instanceof Player ? "" : "Usage: ") . $this->getUsage());
                        return false;
                        break;
                }
                break;
            default:
                $sender->sendMessage(TextFormat::RED . ($sender instanceof Player ? "" : "Usage: ") . $this->getUsage());
                return false;
                break;
        }
        return true;
    }
}
