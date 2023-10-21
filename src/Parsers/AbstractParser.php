<?php

namespace SashaBo\Shortener\Parsers;

use SashaBo\IterableString\MultibyteIterableString;
use SashaBo\Shortener\Parts\AbstractPart;
use SashaBo\Shortener\Parts\Space;
use SashaBo\Shortener\Parts\Word;

abstract class AbstractParser
{
    // ranges from-to
    // https://en.wikipedia.org/wiki/List_of_Unicode_characters
    protected const PUNCTUATION_SYMBOLS = [
        ['!', '/'], [':', '@'], ['[', '`'], ['{', '~'], ['¡', '¿']
    ];
    /**
     * @param string $source
     * @return array<AbstractPart>
     */
    public static function parseString(string $source): array
    {
        return static::parse(new MultibyteIterableString($source));
    }

    /**
     * @param MultibyteIterableString $source
     * @return array<AbstractPart>
     */
    abstract public static function parse(MultibyteIterableString $source): array;

    protected static function readSpace(MultibyteIterableString $source): Space
    {
        $space = '';
        while ($source->valid() && static::isSpace($source)) {
            $space .= $source->current();
            $source->next();
        }
        return new Space($space);
    }

    protected static function readWord(MultibyteIterableString $source): Word
    {
        $word = '';
        while ($source->valid() && static::isWord($source)) {
            $word .= $source->current();
            $source->next();
        }
        return new Word($word);
    }

    abstract protected static function isWord(MultibyteIterableString $source): bool;
    protected static function isSpace(MultibyteIterableString $source): bool
    {
        return trim($source->current()) == '';
    }
    protected static function isSymbol(MultibyteIterableString $source): bool
    {
        $char = $source->current();
        foreach (static::PUNCTUATION_SYMBOLS as $range) {
            if ($char >= $range[0] && $char <= $range[1]) {
                return true;
            }
        }

        return false;
    }
}
