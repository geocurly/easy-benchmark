# easy-benchmark

There is the simple benchmark library for your php code.


:warning: **This library is applicable only for fast estimate performance of your decition**. If you want to find a complete solution, look at [Xdebug](https://xdebug.org/), [XHProf](https://www.php.net/manual/ru/book.xhprof.php) and etc.
-------

[**Examples**](https://github.com/geocurly/easy-benchmark/tree/master/example)

1) Compare array merge functions performance
```php
<?php

use EasyBenchmark\EasyBenchmark;

require __DIR__. "/../vendor/autoload.php";

$values = [];

$arrays = [];
for ($i = 1; $i <=100; $i++) {
    $arrays[] = range(0, 1000);
}

$test = [
    "sequence merge" => function () use($arrays) {
        $arr = [];
        foreach ($arrays as $array) {
            $arr = array_merge($arr, $array);
        }
    },
    "merge unpacked array" => function () use($arrays) {
        array_merge(...$arrays);
    }
];

foreach (EasyBenchmark::run($test, 100) as $result) {
    echo (string) $result . "\n";
}

/*
 * Output: 
 * Name: sequence merge. Iteration 100. Execution time 8.31734 ms
 * Name: merge unpacked array. Iteration 100. Execution time 0.17974 ms.
 */
```

2) Check finction memory usage
```php
<?php

use EasyBenchmark\EasyBenchmark;
use EasyBenchmark\Result;

require __DIR__. "/../vendor/autoload.php";

$storage = new class {
    private array $storage = [];
    public function push($value): void
    {
        $this->storage[] = $value;
    }
};

$testFunctions = [
    "testMemoryUsage" => static function (Result $result) use($storage) {
        $storage->push(random_bytes(random_int(100000, 200000)));
        $result->setMemory(memory_get_usage());
    }
];

/*
 * There is we set third input argument as "false" to get every iteration result
 */
foreach (EasyBenchmark::run($testFunctions, 10, false) as $result) {
    echo "Iteration: {$result->getIter()}. Memory: {$result->humanReadableMemory()} \n";
}


/*
 * Output:
 * Iteration: 1. Memory: 1,04 МБ
 * Iteration: 2. Memory: 1,20 МБ
 * Iteration: 3. Memory: 1,35 МБ
 * Iteration: 4. Memory: 1,46 МБ
 * Iteration: 5. Memory: 1,63 МБ
 * Iteration: 6. Memory: 1,79 МБ
 * Iteration: 7. Memory: 1,90 МБ
 * Iteration: 8. Memory: 2,07 МБ
 * Iteration: 9. Memory: 2,18 МБ
 * Iteration: 10. Memory: 2,35 МБ
 */
```
