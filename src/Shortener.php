<?php

namespace SashaBo\Shortener;

class Shortener
{
    public static function shortenText(string $source, int $length = 50, string $add = '...'): string
    {
        return self::shorten(new Reader($source), $length, $add);
    }

    public static function shortenHtml(string $source, int $length = 50, string $add = '...'): string
    {
        return $source;
    }

    private static function shorten(Reader $reader, int $length, string $add): string
    {
        $sum = 0;
        $words = [];
        foreach ($reader as $word) {
            $newSum = $sum + $word->getLength();
            if ($newSum <= $length) {
                $words[] = $word;
                $sum = $newSum;
            } else {
                break;
            }
        }

        $ret = '';
        foreach ($words as $word) {
            $ret .= $word->get();
        }

        return $reader->valid() ? $ret.$add : $ret;
    }
}
