<?php

namespace Acme\Characters;

class Alphanumeric
{
    public static function asArray(): array
    {
        // [0-9a-z]
        return array_map('chr', array_merge(range(48, 57), range(97, 122)));
    }
}
