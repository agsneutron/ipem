<?php

require_once 'vendor/autoload.php';

use BotMan\BotMan\BotMan;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\SymfonyCache;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Messages\Attachments\Image;

// use BotMan\BotMan\Cache\DoctrineCache;
// use Doctrine\Common\Cache\FilesystemCache;

//require_once('OnboardingConversation.php');
//require_once('AyudaGeneralConversation.php');
require_once('AyudaConversation.php');
require_once 'AyudaBloquesConversation.php';

$config = [];

DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);

$adapter = new FilesystemAdapter();

$botman = BotManFactory::create($config, new SymfonyCache($adapter));

// $botman = BotManFactory::create($config, new DoctrineCache(new FilesystemCache('/cache/')));


$botman->hears('.*(hola|buenas|buenos días|buenos dias|buenas tardes|buenas noches|menu).*', function (BotMan $bot,$word) {
    //Se espera un segundo
    $bot->typesAndWaits(1);
    //Se inicia la conversación
    $bot->startConversation(new AyudaBloquesConversation);
});

$botman->fallback(function($bot) {
    $bot->reply('Lo siento no te puedo ayudar');
  });

$botman->listen();