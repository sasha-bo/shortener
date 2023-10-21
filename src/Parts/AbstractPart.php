<?php

namespace SashaBo\Shortener\Parts;

abstract class AbstractPart implements \Stringable
{
    public function __construct(
        protected readonly string $value
    ) {
    }

    public function __toString()
    {
        return $this->value;
    }

    public function getLength(): int
    {
        return 0;
    }

    public function getSpaceLength(): int
    {
        return 0;
    }

    public function doesMatter(): bool
    {
        return $this->getLength() > 0;
    }
}
