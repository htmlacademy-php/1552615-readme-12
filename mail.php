<?php

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

require_once('vendor/autoload.php');

$dsn = 'smtp://kilnur:8855669@smtp.rambler.ru:465';
$transport = Transport::fromDsn($dsn);
$message = new Email();
$mailer = new Mailer($transport);
$message->from("kilnur@rambler.ru");
