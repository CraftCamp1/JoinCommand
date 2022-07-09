<?php

namespace CraftCamp\JoinCommand;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\console\ConsoleCommandSender;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\Config;

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener {
	
    /** @var Config */
    public $config;

    public function onEnable() : void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        if(!file_exists($this->getDataFolder() . "Config.yml")) {
            $this->config = (new Config($this->getDataFolder() . "Config.yml", Config::YAML, [
                /*Executes Command on Console */
                "Server Command"=> "say hello {player}",
                /*Executes Command as Player*/
                "Player Command"=> "say hi",
                /* Enables/Disables Commands */
                "Server Command Enabled"=>"true",
                "Player Command Enabled"=>"true"
            ]));
        } else {
            $this->config = (new Config($this->getDataFolder() . "Config.yml", Config::YAML, []));
        }
    }

	public function onPlayerJoin(PlayerJoinEvent $event): void {

		$player = $event->getPlayer();
		$config = new Config($this->getDataFolder() . "config.yml", Config::YAML, []);
		$pcmd = str_replace(["{player}"], [$player->getName()], $this->getConfig()->get("Player Command"));
		$ccmd = str_replace(["{player}"], [$player->getName()], $this->getConfig()->get("Server Command"));
				if($config->get("Player Command Enabled") === "true"){
	        $this->getServer()->dispatchCommand($player, $pcmd);
	    }
	    if($config->get("Server Command Enabled") === "true"){
	        $this->getServer()->dispatchCommand(new ConsoleCommandSender($this->getServer(), $this->getServer()->getLanguage()), $ccmd);
	    }
	}
}
