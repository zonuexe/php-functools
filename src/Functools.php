<?php
namespace Teto;

/**
 * Functional toolbox
 *
 * @package    Teto
 * @author     USAMI Kenta <tadsan@zonu.me>
 * @copyright  2015 USAMI Kenta
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
final class Functools
{
    private function __construct() {}

    /**
     * @return PartialCallable
     */
    public static function partial(
        callable $callback,
        array $arguments = [],
        $pos = null
    ) {
        return new Functools\PartialCallable($callback, $arguments, $pos);
    }

    /**
     * Returns an indication of the number of arguments accepted by a callable.
     *
     * @param  callable $callback
     * @return int
     */
    public static function arity (callable $callback)
    {
        if (is_array($callback) ||
            (is_string($callback) && strpos($callback, '::') !== false)) {
            list($class, $method) = is_string($callback) ? explode('::', $callback) : $callback;
            $reflection = (new \ReflectionClass($class))->getMethod($method);
        } elseif (is_object($callback) && !($callback instanceof \Closure)) {
            $reflection = (new \ReflectionClass($callback))->getMethod('__invoke');
        } else {
            $reflection = new \ReflectionFunction($callback);
        }

        return $reflection->getNumberOfParameters();
    }

    /**
     * Make curried callable object
     *
     * @param  callable $callback
     * @return Functools\CurriedCallable
     */
    public static function curry(callable $callback)
    {
        return new Functools\CurriedCallable($callback, self::arity($callback), []);
    }
}