<?php

namespace SashaBo\Shortener\Parsers;

use SashaBo\IterableString\MultibyteIterableString;
use SashaBo\Shortener\Parts\Space;
use SashaBo\Shortener\Parts\Symbol;
use SashaBo\Shortener\Parts\Word;

class TextParser extends AbstractParser
{
    public static function parse(MultibyteIterableString $source): array
    {
        $ret = [];
        while ($source->valid()) {
            if (static::isSpace($source)) {
                $ret[] = new Space(static::readSpace($source));
            } elseif (static::isSymbol($source)) {
                $ret[] = new Symbol($source->current());
                $source->next();
            } elseif (static::isWord($source)) {
                $ret[] = new Word(static::readWord($source));
            } else {
                throw new \Exception('Undefined symbol ['.$source->current().']');
            }
        }
        return $ret;
    }

    protected static function isWord(MultibyteIterableString $source): bool
    {
        return !static::isSpace($source) && !static::isSymbol($source);
    }
}
