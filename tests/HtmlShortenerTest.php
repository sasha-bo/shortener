<?php

use PHPUnit\Framework\TestCase;
use SashaBo\Shortener\HtmlShortener;

final class HtmlShortenerTest extends TestCase
{
    public function testHtmlShortening(): void
    {
        $shortener = new HtmlShortener('aaa <a><b>bbb</b> ccc</a> ddd');
        $addition = ' <d>eee</d>';
        foreach (
            [
                50 => 'aaa <a><b>bbb</b> ccc</a> ddd',
                15 => 'aaa <a><b>bbb</b> ccc</a> ddd',
                14 => 'aaa <a><b>bbb</b></a>'.$addition,
                11 => 'aaa <a><b>bbb</b></a>'.$addition,
                10 => 'aaa'.$addition,
                7 => 'aaa'.$addition,
                6 => 'aa'.$addition,
            ] as $length => $expected
        ) {
            $actual = $shortener->shorten($length, $addition);
            $this->assertEquals($expected, $actual, 'Incorrect shortening to '.$length);
        }
    }

    public function testSpecialCharacters(): void
    {
        $shortener = new HtmlShortener('aaa&gt;bbb<i>&amp;ccc&nbsp;</i>ddd');
        $addition = '<img>';
        foreach (
            [
                50 => 'aaa&gt;bbb<i>&amp;ccc&nbsp;</i>ddd',
                15 => 'aaa&gt;bbb<i>&amp;ccc&nbsp;</i>ddd',
                14 => 'aaa&gt;bbb<i>&amp;ccc</i>'.$addition,
                11 => 'aaa&gt;bbb<i>&amp;ccc</i>'.$addition,
                10 => 'aaa&gt;bbb'.$addition,
                7 => 'aaa&gt;bbb'.$addition,
                4 => 'aaa'.$addition,
            ] as $length => $expected
        ) {
            $actual = $shortener->shorten($length, $addition);
            $this->assertEquals($expected, $actual, 'Incorrect shortening to '.$length);
        }
    }
}
