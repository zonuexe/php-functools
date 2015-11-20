<?php
namespace Teto\Functools;
use Teto\Functools as f;
use Teto\Functools\Operator as _;

final class MemoizerTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $expected = [1, 1, 2, 3, 5, 8, 13, 21, 34, 55, 89, 144, 233, 377, 610, 987, 1597, 2584,
                     4181, 6765, 10946, 17711, 28657, 46368, 75025, 121393, 196418, 317811];

        $fib1 = function ($n) use (&$fib1) {
            return ($n < 2) ? $n : $fib1($n - 1) + $fib1($n - 2);
        };

        $begin1  = microtime(true);
        $actual1 = array_map($fib1, range(1, 28));
        $end1    = microtime(true);

        $this->assertEquals($expected, $actual1);

        $fib2 = f::memoize(function ($fib, $n) {
            return ($n < 2) ? $n : $fib($n - 1) + $fib($n - 2);
        }, [0, 1]);

        $begin2  = microtime(true);
        $actual2 = array_map($fib2, range(1, 28));
        $end2    = microtime(true);

        $this->assertEquals($expected, $actual2);

        $this->assertLessThan($end1 - $begin1, $end2 - $begin2);
    }
}