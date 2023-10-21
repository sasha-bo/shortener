<?php

namespace SashaBo\Shortener;

use SashaBo\Shortener\Parsers\HtmlParser;
use SashaBo\Shortener\Parsers\TextParser;
use SashaBo\Shortener\Parts\AbstractPart;
use SashaBo\Shortener\Parts\HtmlTag;
use SashaBo\Shortener\Parts\Space;
use SashaBo\Shortener\Parts\Word;
use SashaBo\Shortener\Readers\AbstractReader;
use SashaBo\Shortener\Readers\TextReader;

class HtmlShortener extends AbstractShortener
{
    protected static function parse(string $source): array
    {
        return HtmlParser::parseString($source);
    }

    public function shorten(int $length = 50, string $addition = '...'): string
    {
        $cutLength = $length - static::countAdditionLength($addition);
        $partsNumber = $this->getPartsNumber($length, $cutLength);
        if ($partsNumber == 0) {
            return $this->cutFirstWordAndImplode($cutLength).$addition;
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

    protected function implode(int $partsNumber): string
    {
        return parent::implode($partsNumber).$this->getClosingTags($partsNumber);
    }

    protected function getClosingTags(int $partNumber): string
    {
        $ret = '';
        $tags = [];
        $cnt = 0;
        foreach ($this->parsed as $part) {
            if ($part instanceof HtmlTag) {
                $isClosing = $part->isClosing();
                $name = $part->getName();
                if ($cnt < $partNumber) {
                    if ($isClosing) {
                        $no = array_search($name, $tags, true);
                        if ($no !== false) {
                            unset($tags[$no]);
                        }
                    } else {
                        $tags[] = $name;
                    }
                } else {
                    if ($isClosing) {
                        $no = array_search($name, $tags, true);
                        if ($no !== false) {
                            unset($tags[$no]);
                            $ret .= $part;
                        }
                    }
                }
            }
            $cnt ++;
        }

        return $ret;
    }
}
