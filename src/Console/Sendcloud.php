<?php

namespace Tnt\Sendcloud\Console;

use Oak\Console\Command\Command;
use Oak\Console\Command\Signature;

class Sendcloud extends Command
{
    protected function createSignature(Signature $signature): Signature
    {
        return $signature
            ->setName('sendcloud')
            ->addSubCommand(SyncShipmentMethods::class)
        ;
    }
}