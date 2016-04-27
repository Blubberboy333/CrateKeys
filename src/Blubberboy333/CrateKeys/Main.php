<?

namespace Blubberboy333\CrateKeys;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener{
	public function onEnable(){
		$this->saveDefaultConfig();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$buyCraft = $this->getServer()->getPluginManager()->getPlugin("BuyCraft");
		if($buycraft == null){
			$this->getLogger(TextFormat::RED."You don't have BuyCraft! It is recomended that you use BuyCraft!");
		}
		$this->getLogger()->info(TextFormat::GREEN."CrateKeys by Blubberboy333 is loaded!");
	}
	
	public function onDisable(){
		$this->getLogger(TextFormat::RED."Uh oh, CrateKeys was disabled!");
	}
	
	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		if(strtolower($command->getName()) == "cratekey"){
			if($sender->hasPermission("cratekey") || $sender->hasPermission("cratekey.give")){
				if($this->getConfig()->get("PlayerGive") == false && $sender instanceof Player){
					$sender->sendMessage(TextFormat::RED."You don't have permission to use that command!");
					return true;
				}else{
					if(isset($args[0])){
						$player = $this->getServer()->getPlayer($args[0]);
						if($player instanceof Player){
							$name = $player->getName();
							$item = Item::get("tripwire_hook");
							$player->getInventory()->addItem($item);
							$commands = $this->getConfig()->get("Commands");
							foreach($commands as $i){
								$this->getServer()->dispatchCommand(new ConsoleCommandSender, str_replace(array("{PLAYER}", "{NAME}"), $player, $player->getName())));
							}
							$sender->sendMessage("Gave ".$player->getName()." a CrateKey!");
							if($sender instanceof Player){
								$this->getLogger()->info(TextFormat::YELLOW.$sender->getName()." gave ".$name." a CrateKey!");
							}
							return true;
						}else{
							$sender->sendMessage("That player isn't online!");
							return true;
						}
					}else{
						$sender->sendMessage("You need to specify a player!");
						return false;
					}
				}
			}else{
				$sender->sendMessage(TextFormat::RED."You don't have permission to do that!");
				return true;
			}
		}
	}
	
	public function onPlayerInteractEvent(PlayerInteractEvent $event){
		$player = $event->getPlayer();
		$heldItem= $player->getInvetory()->getItemInHand();
		$block = $event->getBlock();
		$location = array($block->x, $block->y, $block->z);
		$level = $block->getLevel();
		if($heldItem = Item::get(131)){
			if($location[0] == $this->getConfig()->get("X") && $location[1] == $this->getConfig()->get("Y") && $location[2] == $this->getConfig()->get("Z") && $lvel == $this->getConfig()->get("World")){
				for($i = 1; $i <= $this->getConfig()->get("Number"); $i++){
					$rand = $this->getConfig()->get("Items");
					$player->getInventory()->addItem(Item::get($rand));
				}
			}
		}else{
			$player->sendMessage(TextFormat::YELLOW."You need to be holding a CrateKey to get CrateKey items!");
		}
	}
}
