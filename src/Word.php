<?php

namespace SashaBo\Shortener;

/*
 * A fragment of text, which may start with space but must end non-space symbol,
 * the last one before next space of end of source. For example:
 * 'Lorem ipsum dolor' => [Lorem][ ipsum][ dolor]
 */
class Word
{
    public function __construct(
        private readonly string $word,
        private readonly int $length
    ) {
    }

    public function get(): string
    {
        return $this->word;
    }
    public function getLength(): int
    {
        return $this->length;
    }
}
