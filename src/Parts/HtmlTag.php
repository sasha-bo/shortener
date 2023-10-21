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
        preg_replace_callback('%^</?([^>\s]+)%', function (array $matches) use (&$ret): string {
            $ret = $matches[1];
            return $matches[1];
        }, $this->value);

        return strtolower($ret);
    }
}
