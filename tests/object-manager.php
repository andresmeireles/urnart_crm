<?php
use App\Kernel;

require __DIR__.'/../vendor/autoload.php';

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();

if ($kernel->getContainer() === null) {
    throw new \Exception('deu arrado algo aqui');
}

return $kernel->getContainer()->get('doctrine')->getManager();