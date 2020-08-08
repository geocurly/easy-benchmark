<?php

declare(strict_types=1);

namespace EasyBenchmark;

use Generator;

class EasyBenchmark
{
    /**
     * Benchmark your functions with execution time and memory usage result
     *
     * @param callable[]    $functionsForTest   test callable map
     * @param int           $iteration          Iteration count
     * @param bool          $accumulate         Accumulate execution time over all iteration
     *
     * @example
     * <pre>
     *      $resultGenerator = EasyBenchmark::run(
     *          ["testName" => fn(Result $res, int $startMemoryUsage) => // your callable implementation],
     *          10000, // There is iteration count
     *          true,
     *      )
     * </pre
     *
     * @return Generator|Result[]
     */
    public static function run(array $functionsForTest, int $iteration, bool $accumulate = true): Generator
    {
        foreach ($functionsForTest as $key => $testFunction) {
            $args = [$testFunction, (string) $key, $iteration];
            if ($accumulate) {
                yield static::accumulate(...$args);
            } else {
                yield from static::forEach(...$args);
            }
        }
    }

    /**
     * Benchmark for each iteration
     *
     * @param callable $function
     * @param string $name
     * @param int $iteration
     * @return Generator
     */
    private static function forEach(callable $function, string $name, int $iteration): Generator
    {
        $result = new Result($name);
        for ($iter = 1; $iter <= $iteration; $iter++) {
            [$startTime, $startMemory] = [microtime(true), memory_get_usage()];
            $function($result, $startMemory);
            yield $result->setTime(microtime(true) - $startTime)->setIter($iter);
        }
    }

    /**
     * Benchmark over all iteration
     *
     * @param callable $function
     * @param string $name
     * @param int $iteration
     * @return Result
     */
    private static function accumulate(callable $function, string $name, int $iteration): Result
    {
        $result = new Result($name);
        [$startTime, $startMemory] = [microtime(true), memory_get_usage()];
        for ($iter = 1; $iter <= $iteration; $iter++) {
            $function($result, $startMemory);
        }

        return $result->setTime(microtime(true) - $startTime)->setIter($iteration);
    }
}