<?php

namespace AkmalFairuz\CoronaData\task;

use AkmalFairuz\CoronaData\form\CoronaForm;
use pocketmine\Player;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class APIThread extends AsyncTask
{
    /** @var array */
    public $result;

    /** @var int */
    public $type = 0;

    /** @var string */
    public $countryName;

    public const API_URL = "https://api.kawalcorona.com/";

    public function __construct(Player $player, int $type, string $countryName = "")
    {
        $this->type = $type;
        $this->countryName = $countryName;
        $this->storeLocal($player);
    }

    public function onRun()
    {
        $context=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        $result = file_get_contents(self::API_URL, false, stream_context_create($context));
        $res = (array) json_decode($result, true);
        $this->result = $res;
    }

    public function onCompletion(Server $server)
    {
        $form = new CoronaForm();
        $player = $this->fetchLocal();
        /** @var Player $player */
        if($this->type === 0) {
            $form->seeAllCountry($player, (array) $this->result);
        } else {
            $form->searchResult($player, (array) $this->result, $this->countryName);
        }
    }
}