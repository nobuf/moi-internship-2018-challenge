<?php

namespace Acme\Characters;

class Hex
{
    public static function asArray(): array
    {
        // [0-9a-f]
        return array_map('chr', array_merge(range(48, 57), range(97, 102)));
    }
}
