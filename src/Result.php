<?php

declare(strict_types=1);

namespace EasyBenchmark;

class Result
{
    private ?int $memory = null;
    private float $time;
    private int $iter;
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function setIter(int $iter): self
    {
        $this->iter = $iter;
        return $this;
    }

    public function getMemory(): ?int
    {
        return $this->memory;
    }

    public function getTime(): float
    {
        return $this->time;
    }

    public function getIter(): int
    {
        return $this->iter;
    }

    public function setMemory(?int $memory): self
    {
        $this->memory = $memory;
        return $this;
    }

    public function setTime(float $time): self
    {
        $this->time = $time;
        return $this;
    }

    public function __toString(): string
    {
        $message = "Name: $this->name. Iteration $this->iter. Execution time {$this->humanReadableTime()}.";
        if ($this->memory !== null) {
            $message .= " Memory {$this->humanReadableMemory()}.";
        }

        return $message;
    }

    /**
     * Convert memory to readable format
     * @return string
     */
    public function humanReadableMemory(): string
    {
        $si_prefix = ['Б', 'КБ', 'МБ', 'ГБ', 'ТБ', 'ЭБ', 'ЗБ', 'ЙБ'];
        $base = 1024;
        $class = min((int)log($this->memory, $base), count($si_prefix) - 1);

        return strtr(sprintf('%1.2f %s', $this->memory / ($base ** $class), $si_prefix[$class]), ['.' => ',']);
    }

    public function humanReadableTime(): string
    {
        return number_format($this->time, 5). " ms";
    }
}
