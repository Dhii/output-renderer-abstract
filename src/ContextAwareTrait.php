<?php

namespace Dhii\Output;

use Exception as RootException;
use InvalidArgumentException;
use Dhii\Util\String\StringableInterface as Stringable;
use Psr\Container\ContainerInterface;

/**
 * Common functionality for objects that are aware of a context.
 *
 * @since [*next-version*]
 */
trait ContextAwareTrait
{
    /**
     * The context.
     *
     * @since [*next-version*]
     *
     * @var ContainerInterface|null
     */
    protected $context;

    /**
     * Retrieves the context associated with this instance.
     *
     * @since [*next-version*]
     *
     * @return ContainerInterface|null The context.
     */
    protected function _getContext()
    {
        return $this->context;
    }

    /**
     * Sets the context for this instance.
     *
     * @since [*next-version*]
     *
     * @param ContainerInterface|null $context The context instance, or null.
     */
    protected function _setContext($context)
    {
        if ($context !== null && !($context instanceof ContainerInterface)) {
            throw $this->_createInvalidArgumentException(
                $this->__('Invalid context'),
                null,
                null,
                $context
            );
        }

        $this->context = $context;
    }

    /**
     * Creates a new Dhii invalid argument exception.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable|null $message  The error message, if any.
     * @param int|null               $code     The error code, if any.
     * @param RootException|null     $previous The inner exception for chaining, if any.
     * @param mixed|null             $argument The invalid argument, if any.
     *
     * @return InvalidArgumentException The new exception.
     */
    abstract protected function _createInvalidArgumentException(
        $message = null,
        $code = null,
        RootException $previous = null,
        $argument = null
    );

    /**
     * Translates a string, and replaces placeholders.
     *
     * @since [*next-version*]
     * @see   sprintf()
     *
     * @param string $string  The format string to translate.
     * @param array  $args    Placeholder values to replace in the string.
     * @param mixed  $context The context for translation.
     *
     * @return string The translated string.
     */
    abstract protected function __($string, $args = [], $context = null);
}
