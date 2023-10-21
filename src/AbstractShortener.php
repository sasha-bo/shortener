<?php

namespace SashaBo\Shortener;

use SashaBo\Shortener\Parsers\HtmlParser;
use SashaBo\Shortener\Parts\AbstractPart;
use SashaBo\Shortener\Parts\HtmlTag;
use SashaBo\Shortener\Parts\Space;
use SashaBo\Shortener\Parts\Word;
use SashaBo\Shortener\Readers\AbstractReader;
use SashaBo\Shortener\Readers\TextReader;

abstract class AbstractShortener
{
    /** @var array<AbstractPart>  */
    protected readonly array $parsed;
    public function __construct(
        protected readonly string $source
    ) {
        $this->parsed = static::parse($this->source);
    }

    /**
     * @param string $source
     * @return array<AbstractPart>
     */
    abstract protected static function parse(string $source): array;

    abstract public function shorten(int $length = 50, string $addition = '...'): string;

    /**
     * @param int $maxLength
     * @param int $cutLength
     * @param bool $multiSpace
     * @return int
     *      How many parts to use for $length. 0 means even the first word is longer
     */
    protected function getPartsNumber(int $maxLength, int $cutLength, bool $multiSpace = false): int
    {
        $sum = 0;
        $partsNum = 0;
        $cutPartsNum = 0;
        // to count only one space
        $previousSpace = true;
        // to ignore few empty parts at the end
        $ignoreTail = 0;
        // to return 0 if even the first word is longer
        $matteredPartPassed = false;
        foreach ($this->parsed as $part) {
            $partLength = $part->getLength();
            if ($partLength > 0) {
                $sum += $partLength;
                $previousSpace = false;
            } else {
                $spaceLength = $part->getSpaceLength();
                if ($spaceLength > 0) {
                    if ($multiSpace) {
                        $sum += $spaceLength;
                    } elseif (!$previousSpace) {
                        $sum += 1;
                    }
                    $previousSpace = true;
                }
            }
            if ($sum <= $maxLength) {
                $partsNum ++;
                if ($sum <= $cutLength) {
                    $cutPartsNum = $partsNum;
                    if ($part->doesMatter()) {
                        $ignoreTail = 0;
                    } else {
                        $ignoreTail ++;
                    }
                }
            } elseif (!$matteredPartPassed) {
                return 0;
            } else {
                break;
            }
            if ($part->doesMatter()) {
                $matteredPartPassed = true;
            }
        }
        return $partsNum < count($this->parsed) ? $cutPartsNum - $ignoreTail : $partsNum;
    }

    protected function implode(int $partsNumber): string
    {
        return implode('', array_slice($this->parsed, 0, $partsNumber));
    }
    protected function countAdditionLength(string $addition, bool $multiSpace = false): int
    {
        $ret = 0;
        $previousSpace = false;
        foreach (static::parse($addition) as $part) {
            $length = $part->getLength();
            if ($length > 0) {
                $ret += $length;
            } else {
                $spaceLength = $part->getSpaceLength();
                if ($spaceLength > 0) {
                    if ($multiSpace) {
                        $ret += $spaceLength;
                    } elseif (!$previousSpace) {
                        $ret += 1;
                    }
                    $previousSpace = true;
                }
            }
        }
        return $ret;
    }
}
