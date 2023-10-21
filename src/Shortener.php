<?php

namespace SashaBo\Shortener;

use SashaBo\Shortener\Parts\AbstractPart;
use SashaBo\Shortener\Parts\Space;
use SashaBo\Shortener\Parts\Word;
use SashaBo\Shortener\Readers\AbstractReader;
use SashaBo\Shortener\Readers\TextReader;

class Shortener
{
    public static function shortenText(string $source, int $length = 50, string $add = '...', bool $multiSpace = false): string
    {
        return (new TextShortener($source))->shorten($length, $add, $multiSpace);
    }

    public static function shortenHtml(string $source, int $length = 50, string $add = '...'): string
    {
        return (new HtmlShortener($source))->shorten($length, $add);
    }
}
