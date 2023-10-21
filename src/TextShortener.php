<?php

namespace SashaBo\Shortener;

use SashaBo\Shortener\Parsers\TextParser;
use SashaBo\Shortener\Parts\AbstractPart;
use SashaBo\Shortener\Parts\Space;
use SashaBo\Shortener\Parts\Word;
use SashaBo\Shortener\Readers\AbstractReader;
use SashaBo\Shortener\Readers\TextReader;

class TextShortener extends AbstractShortener
{
    protected static function parse(string $source): array
    {
        return TextParser::parseString($source);
    }

    public function shorten(int $length = 50, string $addition = '...', bool $multiSpace = false): string
    {
        $cutLength = $length - static::countAdditionLength($addition, $multiSpace);
        $partsNumber = $this->getPartsNumber($length, $cutLength, $multiSpace);
        if ($partsNumber == 0) {
            return $this->cutFirstWordAndImplode($cutLength, $multiSpace).$addition;
        } elseif ($partsNumber < count($this->parsed)) {
            return $this->implode($partsNumber).$addition;
        } else {
            return $this->source;
        }
    }

    protected function cutFirstWordAndImplode(int $length, bool $multiSpace = false): string
    {
        $ret = '';
        foreach ($this->parsed as $part) {
            if ($part instanceof Word) {
                return $ret.$part->shorten($length);
            } else {
                $length -= $part->getLength();
                $ret .= $part;
            }
        }
        return '';
    }
}
