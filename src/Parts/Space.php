<?php

namespace SashaBo\Shortener\Parts;

class Space extends AbstractPart
{
    public function getSpaceLength(): int
    {
        return strlen($this->value);
    }
}
