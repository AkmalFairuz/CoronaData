<?php


namespace AkmalFairuz\CoronaData\command;


use AkmalFairuz\CoronaData\form\CoronaForm;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class CoronaCommand extends PluginCommand
{
    public function __construct(string $name, Plugin $owner)
    {
        parent::__construct($name, $owner);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(!$sender instanceof Player) {
            return true;
        }
        $form = new CoronaForm();
        $form->init($sender);
        return true;
    }
}