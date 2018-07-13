<?php

namespace Acme\Characters;

class Numeric
{
    public static function asArray(): array
    {
        // [0-9]
        return array_map('chr', range(48, 57));
    }
}
