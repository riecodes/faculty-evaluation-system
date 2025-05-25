<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

return [
    'host' => $_ENV['MAIL_HOST'],
    'port' => $_ENV['MAIL_PORT'],
    'username' => $_ENV['MAIL_USERNAME'],
    'password' => $_ENV['MAIL_PASSWORD'],
    'encryption' => $_ENV['MAIL_ENCRYPTION'],
    'from' => [
        'address' => $_ENV['MAIL_FROM_ADDRESS'],
        'name' => $_ENV['MAIL_FROM_NAME']
    ]
]; 