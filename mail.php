<?php

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;

require_once('vendor/autoload.php');

$dsn = 'smtp://kilnur:8855669@smtp.rambler.ru:465';
$transport = Transport::fromDsn($dsn);
$mailer = new Mailer($transport);
$sender = 'kilnur@rambler.ru';
