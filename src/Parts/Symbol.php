<?php

namespace SashaBo\Shortener\Parts;

class Symbol extends AbstractPart
{
    public function getLength(): int
    {
        return 1;
    }

    public function doesMatter(): bool
    {
        return false;
    }
}
