<?php


namespace AkmalFairuz\CoronaData\form;


use AkmalFairuz\CoronaData\form\api\FormAPI;
use pocketmine\Player;
use AkmalFairuz\CoronaData\task\APIThread;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class CoronaForm
{
    public function __construct()
    {
    }

    public function init(Player $player) {
        $form = (new FormAPI())->createSimpleForm(function (Player $player, $data) {
            if($data !== null) {
                if($data === 0) {
                    $task = new APIThread($player, 0);
                    Server::getInstance()->getAsyncPool()->submitTask($task);
                    $player->sendMessage("Please wait... Requesting data into api.kawalcorona.com");
                } else {
                    $this->searchByCountryName($player);
                }
            }
        });
        $form->setTitle("Corona Data");
        $form->setContent("");
        $form->addButton("See all country");
        $form->addButton("Search by country name");
        $form->sendToPlayer($player);
    }

    public function seeAllCountry(Player $player, array $cData) {
        $form = (new FormAPI())->createSimpleForm(function (Player $player, $data) {
            if($data !== null) {
                $this->init($player);
            }
        });
        $form->setTitle("All Country");
        $content = "";
        foreach($cData as $c) {
            $a = $c["attributes"];
            $content .= TextFormat::GRAY . $a["Country_Region"] . ":". TextFormat::YELLOW."\n Confirmed: ".number_format($a["Confirmed"])."\n Deaths: ".number_format($a["Deaths"])."\n Recovered: ".number_format($a["Recovered"])."\n\n";
        }
        $form->setContent($content);
        $form->addButton("Back");
        $form->sendToPlayer($player);
    }

    public function searchByCountryName(Player $player) {
        $form = (new FormAPI())->createCustomForm(function (Player $player, $data) {
            if(empty($data)) {
                return;
            }
            $task = new APIThread($player, 1, $data[0]);
            Server::getInstance()->getAsyncPool()->submitTask($task);
            $player->sendMessage("Please wait... Requesting data into api.kawalcorona.com");
        });
        $form->setTitle("Search by country name");
        $form->addInput("Country name: ");
        $form->sendToPlayer($player);
    }

    public function searchResult(Player $player, array $data, string $countryName) {
        $form = (new FormAPI())->createSimpleForm(function (Player $player, $data) {
            if($data !== null) {
                $this->init($player);
            }
        });
        $form->setTitle("Search result");
        $content = "Not found";
        foreach($data as $cdata) {
            if(strtolower($cdata["attributes"]["Country_Region"]) == strtolower($countryName)) {
                $content = $cdata["attributes"];
                $content = TextFormat::GRAY . $content["Country_Region"].":".TextFormat::YELLOW."\n Confirmed: ".number_format($content["Confirmed"]) . "\n"." Deaths: ".number_format($content["Deaths"])."\n Recovered".number_format($content["Recovered"])."\n\n";
            }
        }
        $form->setContent($content);
        $form->addButton("Back");
        $form->sendToPlayer($player);
    }
}