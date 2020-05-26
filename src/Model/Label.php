<?php

namespace Tnt\Sendcloud\Model;

use dry\orm\Model;
use dry\orm\special\JSON;

class Label extends Model
{
    const TABLE = 'sendcloud_label';

    public static $special_fields = [
        'normal_printer' => JSON::class,
    ];
}