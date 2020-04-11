<?php

namespace AkmalFairuz\CoronaData;

use AkmalFairuz\CoronaData\command\CoronaCommand;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase
{
    public function onEnable()
    {
        $this->getServer()->getCommandMap()->register("corona", new CoronaCommand("corona", $this));
    }

    public function onDisable()
    {
    }
}