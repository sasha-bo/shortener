<?php

namespace SashaBo\Shortener\Parts;

class HtmlSpecialChar extends AbstractPart
{
    public function getLength(): int
    {
        return $this->isSpace() ? 0 : 1;
    }

    public function getSpaceLength(): int
    {
        return $this->isSpace() ? 1 : 0;
    }

    private function isSpace(): bool
    {
        return $this->value == '&nbsp;';
    }

    public function doesMatter(): bool
    {
        return false;
    }
}
