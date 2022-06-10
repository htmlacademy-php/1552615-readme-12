<?php

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

require 'vendor/autoload.php';

$dsn = 'smtp://kilnur1988:239841@smtp.rambler.ru:465';
$transport = Transport::fromDsn($dsn);
$message = new Email();
$mailer = new Mailer($transport);
