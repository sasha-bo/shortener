<?php

namespace SashaBo\Shortener\Parsers;

use SashaBo\IterableString\MultibyteIterableString;
use SashaBo\Shortener\Parts\HtmlComment;
use SashaBo\Shortener\Parts\HtmlSpecialChar;
use SashaBo\Shortener\Parts\HtmlTag;
use SashaBo\Shortener\Parts\Space;
use SashaBo\Shortener\Parts\Symbol;
use SashaBo\Shortener\Parts\Word;

class HtmlParser extends AbstractParser
{
    public static function parse(MultibyteIterableString $source): array
    {
        $ret = [];
        while ($source->valid()) {
            if (static::isSpace($source)) {
                $ret[] = static::readSpace($source);
            } elseif (static::isCommentStart($source)) {
                $ret[] = static::readComment($source);
            } elseif (static::isTagStart($source)) {
                $ret[] = static::readTag($source);
            } elseif (static::isAmpersand($source)) {
                $ret[] = static::readSpecial($source);
            } elseif (static::isSymbol($source)) {
                $ret[] = new Symbol($source->current());
                $source->next();
            } elseif (static::isWord($source)) {
                $ret[] = static::readWord($source);
            } else {
                throw new \Exception('Undefined symbol ['.$source->current().']');
            }
        }
        return $ret;
    }

    protected static function readTag(MultibyteIterableString $source): HtmlTag|HtmlComment
    {
        $tag = $source->current();
        $source->next();
        while ($source->valid() && !static::isTagEnd($source)) {
            $tag .= $source->current();
            $source->next();
        }
        if ($source->valid()) {
            $tag .= $source->current();
            $source->next();
        } else {
            $tag .= '>';
        }
        return new HtmlTag($tag);
    }

    protected static function readComment(MultibyteIterableString $source): HtmlComment
    {
        $comment = $source->current(4);
        $source->next(4);
        while ($source->valid() && !static::isCommentEnd($source)) {
            $comment .= $source->current();
            $source->next();
        }
        if ($source->valid()) {
            $comment .= $source->current(3);
            $source->next(3);
        } else {
            $comment .= '-->';
        }
        return new HtmlComment($comment);
    }

    protected static function readSpecial(MultibyteIterableString $source): HtmlSpecialChar
    {
        $special = $source->current();
        $source->next();
        while ($source->valid() && !static::isSemicolon($source)) {
            $special .= $source->current();
            $source->next();
        }
        $special .= $source->current();
        $source->next();
        return new HtmlSpecialChar($special);
    }

    protected static function isWord(MultibyteIterableString $source): bool
    {
        return !static::isSpace($source)
            && !static::isAmpersand($source)
            && !static::isTagStart($source)
            && !static::isSymbol($source);
    }

    protected static function isAmpersand(MultibyteIterableString $source): bool
    {
        return $source->current() == '&';
    }

    protected static function isSemicolon(MultibyteIterableString $source): bool
    {
        return $source->current() == ';';
    }

    protected static function isTagStart(MultibyteIterableString $source): bool
    {
        return $source->current() == '<';
    }

    protected static function isTagEnd(MultibyteIterableString $source): bool
    {
        return $source->current() == '>';
    }

    protected static function isCommentStart(MultibyteIterableString $source): bool
    {
        return $source->current(4) == '<!--';
    }

    protected static function isCommentEnd(MultibyteIterableString $source): bool
    {
        return $source->current(3) == '-->';
    }
}
