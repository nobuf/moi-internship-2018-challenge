<?php

namespace Acme;

trait Permutation
{
    private $permutations = [];

    /**
     * @see https://stackoverflow.com/a/5506933/297679
     */
    protected function permute(array $items, array $perms = [])
    {
        if (count($items) === count($this->getHnb()->getCharacters()) - $this->getHnb()->getLevel()) {
            $this->permutations[] = $perms;
            return;
        }
        for ($i = count($items) - 1; $i >= 0; --$i) {
            $newItems = $items;
            $newPerms = $perms;
            list($item) = array_splice($newItems, $i, 1);
            array_unshift($newPerms, $item);
            $this->permute($newItems, $newPerms);
        }
    }

    protected function getPermutations(): array
    {
        return $this->permutations;
    }
}
