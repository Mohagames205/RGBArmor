<?php

namespace mohagames\RGBArmor;


use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Color;


class Main extends PluginBase implements Listener
{
    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        switch ($command->getName()) {
            case "makearmor":
                $this->customiserType($sender);
                return true;
            default:
                return false;
        }
    }


    public function customiserType($player)
    {
        $form = new SimpleForm(function (Player $player, $data) {
            if ($data !== null) {
                switch ($data) {
                    case "0":
                        $this->simpleArmor($player);
                        break;
                    case "1":
                        $this->armorGUI($player);
                        break;
                }
            }
        });
        $form->setTitle("Customiser Type");
        $form->setContent("§lSingle:§r Make a whole set with the same color\n\n§lMultiple:§r Customise each armor piece\n\n");
        $form->addButton("Single");
        $form->addButton("Multiple");
        $player->sendForm($form);
    }

    public function armorGUI($player)
    {
        $form = new CustomForm(function (Player $player, $data) {
            if ($data !== null) {
                if (isset($data[0]) && isset($data[1]) && isset($data[2]) && isset($data[3])) {
                    $helm_rgb = explode(",", $data[0]);
                    $chest_rgb = explode(",", $data[1]);
                    $pants_rgb = explode(",", $data[2]);
                    $boots_rgb = explode(",", $data[3]);
                    if (count($helm_rgb) == 3 && count($chest_rgb) == 3 && count($pants_rgb) == 3 && count($boots_rgb) == 3) {
                        $this->makeHelmet($helm_rgb, $player);
                        $this->makeChest($chest_rgb, $player);
                        $this->makePants($pants_rgb, $player);
                        $this->makeBoots($boots_rgb, $player);
                    } else {
                        $player->sendMessage("§cThis RGB code is invalid!");
                    }
                } else {
                    $player->sendMessage("§cPlease fill in all fields");
                }
            }
        });
        $form->setTitle("Armor GUI");
        $form->addInput("Helmet", "r, g, b");
        $form->addInput("Chestplate", "r, g, b");
        $form->addInput("Pants", "r, g, b");
        $form->addInput("Boots", "r, g, b");
        $player->sendForm($form);
    }

    public function simpleArmor($player)
    {
        $form = new CustomForm(function (Player $player, $data) {
            if ($data !== null) {
                if (isset($data[0])) {
                    $armor_rgb = explode(",", $data[0]);
                    if (count($armor_rgb) == 3) {
                        $this->makeHelmet($armor_rgb, $player);
                        $this->makeChest($armor_rgb, $player);
                        $this->makePants($armor_rgb, $player);
                        $this->makeBoots($armor_rgb, $player);
                    } else {
                        $player->sendMessage("§cThis RGB code is invalid!");
                    }
                } else {
                    $player->sendMessage("§cPlease fill in all fields");
                }
            }
        });
        $form->setTitle("Armor GUI");
        $form->addInput("Armor color", "r, g, b");
        $player->sendForm($form);
    }

    public function makeHelmet(array $array, $player)
    {
        list($r, $g, $b) = $array;
        $item = Item::get(ItemIds::LEATHER_HELMET);
        $item->setCustomColor(new Color($r, $g, $b));
        $player->getInventory()->addItem($item);
    }

    public function makeChest(array $array, $player)
    {
        list($r, $g, $b) = $array;


        $item = Item::get(ItemIds::LEATHER_CHESTPLATE);
        $item->setCustomColor(new Color($r, $g, $b));
        $player->getInventory()->addItem($item);
    }

    public function makeBoots(array $array, $player)
    {
        list($r, $g, $b) = $array;
        $item = Item::get(ItemIds::LEATHER_BOOTS);
        $item->setCustomColor(new Color($r, $g, $b));
        $player->getInventory()->addItem($item);
    }

    public function makePants(array $array, $player)
    {
        list($r, $g, $b) = $array;
        $item = Item::get(ItemIds::LEATHER_PANTS);
        $item->setCustomColor(new Color($r, $g, $b));
        $player->getInventory()->addItem($item);
    }

    /**
     * @param $hex
     * @param bool $alpha
     * @return mixed
     *
     * Not used at this moment
     *
     */
    public function hexToRgb($hex, $alpha = false)
    {
        $hex = str_replace('#', '', $hex);
        $length = strlen($hex);
        $rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
        $rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
        $rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
        if ($alpha) {
            $rgb['a'] = $alpha;
        }
        return $rgb;
    }
}