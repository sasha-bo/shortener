<?php

use PHPUnit\Framework\TestCase;
use SashaBo\Shortener\TextShortener;

final class TextShortenerTest extends TestCase
{
    public function testMultibyteSymbols(): void
    {
        $shortener = new TextShortener('visgaršīgākie āboli Rīgā');
        foreach (
            [
                50 => 'visgaršīgākie āboli Rīgā',
                24 => 'visgaršīgākie āboli Rīgā',
                23 => 'visgaršīgākie āboli @@@',
                22 => 'visgaršīgākie @@@',
                21 => 'visgaršīgākie @@@',
                17 => 'visgaršīgākie @@@',
                16 => 'visgaršīgāki @@@',
            ] as $length => $expected
        ) {
            $actual = $shortener->shorten($length, ' @@@');
            $this->assertEquals($expected, $actual, 'Incorrect shortening to '.$length);
        }
    }

    public function testMultipleSpaces(): void
    {
        $shortener = new TextShortener('visgaršīgākie   āboli Rīgā');
        $this->assertEquals(
            'visgaršīgākie   āboli @@@',
            $shortener->shorten(23, ' @@@')
        );
        $this->assertEquals(
            'visgaršīgākie @@@',
            $shortener->shorten(23, ' @@@', true)
        );
    }

    public function testStartingSpaces(): void
    {
        $shortener = new TextShortener('        visgaršīgākie āboli Rīgā');
        $this->assertEquals(
            '        visgaršīgākie āboli @@@',
            $shortener->shorten(23, ' @@@')
        );
    }

    public function testPunctuationSymbols(): void
    {
        foreach (
            [
                'visgaršīgākie āboli. Rīgā',
                'visgaršīgākie āboli, Rīgā',
                'visgaršīgākie āboli! Rīgā',
                'visgaršīgākie āboli; Rīgā',
                'visgaršīgākie āboli# Rīgā',
            ] as $string
        ) {
            $shortener = new TextShortener($string);
            $this->assertEquals(
                'visgaršīgākie āboli',
                $shortener->shorten(21, '')
            );
            $this->assertEquals(
                'visgaršīgākie āboli',
                $shortener->shorten(19, '')
            );
            $this->assertEquals(
                'visgaršīgākie',
                $shortener->shorten(18, '')
            );
        }
    }
}
