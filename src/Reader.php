<?php

namespace SashaBo\Shortener;

use Iterator;

/** @implements Iterator<int, Word> */
class Reader implements Iterator
{
    protected int $position;
    /**
     * @var array<int, Word>
     *     position => Word
     */
    private array $words;
    private readonly int $length;
    private bool $ended;

    public function __construct(
        private readonly string $source
    ) {
        $this->length = strlen($this->source);
        $this->rewind();
    }

    public function current(): Word
    {
        return $this->words[$this->key()] ?? new Word('', 0);
    }

    public function next(): void
    {
        if (!$this->ended && $this->position < $this->length) {
            $this->readWord();
        } else {
            $this->ended = true;
        }
    }

    public function previous(): void
    {
        $this->ended = false;
        $this->position = (int) array_key_last($this->words);
        array_pop($this->words);
    }

    public function key(): int
    {
        return (int) array_key_last($this->words);
    }

    public function valid(): bool
    {
        return !$this->ended;
    }

    public function rewind(): void
    {
        $this->ended = false;
        $this->position = 0;
        $this->words = [];
        $this->readWord();
    }

    /*********************************************************************************
     * Protected methods
     ********************************************************************************/

    protected function readWord(): void
    {
        $startPosition = $this->position;
        $word = '';
        $length = 0;
        while ($this->position < $this->length) {
            $char = substr($this->source, $this->position, 1);
            if (trim($char) == '') {
                if ($length > 0) {
                    break;
                }
                $word .= $char;
            } else {
                $word .= $char;
                $length ++;
            }
            $this->position ++;
        }

        $this->words[$startPosition] = new Word($word, $length);
    }
}
