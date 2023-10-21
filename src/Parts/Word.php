<?php

namespace SashaBo\Shortener\Parts;

class Word extends AbstractPart
{
    public function getLength(): int
    {
        return mb_strlen($this->value);
    }

    public function shorten(int $length): Word
    {
        return new self(mb_substr($this->value, 0, $length));
    }

    public function add(string $add): Word
    {
        return new self($this->value.$add);
    }
}
