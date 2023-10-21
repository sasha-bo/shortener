<?php

namespace SashaBo\Shortener\Parts;

class HtmlTag extends AbstractPart
{
    public function isClosing(): bool
    {
        return str_starts_with($this->value, '</');
    }

    public function getName(): string
    {
        $ret = '';
        preg_replace_callback('%^</?([^>\s]+)%', function(array $matches) use (&$ret): void {
            $ret = $matches[1];
        }, $this->value);

        return strtolower($ret);
    }
}
