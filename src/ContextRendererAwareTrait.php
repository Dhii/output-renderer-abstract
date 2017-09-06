<?php

namespace Dhii\Output;

use Exception as RootException;
use InvalidArgumentException;
use Dhii\Util\String\StringableInterface as Stringable;

/**
 * Common functionality for objects that are aware of a context renderer.
 *
 * @since [*next-version*]
 */
trait ContextRendererAwareTrait
{
    /**
     * The renderer instance.
     *
     * @since [*next-version*]
     *
     * @var ContextRendererInterface|null
     */
    protected $contextRenderer;

    /**
     * Retrieves the renderer associated with this instance.
     *
     * @since [*next-version*]
     *
     * @return ContextRendererInterface|null The context renderer.
     */
    protected function _getContextRenderer()
    {
        return $this->contextRenderer;
    }

    /**
     * Sets the context renderer for this instance.
     *
     * @since [*next-version*]
     *
     * @param RendererInterface|null $contextRenderer The context renderer instance, or null.
     */
    protected function _setContextRenderer($contextRenderer)
    {
        if ($contextRenderer !== null && !($contextRenderer instanceof RendererInterface)) {
            throw $this->_createInvalidArgumentException(
                $this->__('Invalid context renderer'),
                null,
                null,
                $contextRenderer
            );
        }

        $this->contextRenderer = $contextRenderer;
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
