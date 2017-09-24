<?php

namespace Dhii\Output;

use Exception as RootException;
use InvalidArgumentException;
use Dhii\Util\String\StringableInterface as Stringable;

/**
 * Common functionality for objects that are aware of a renderer.
 *
 * @since [*next-version*]
 */
trait RendererAwareTrait
{
    /**
     * The renderer instance.
     *
     * @since [*next-version*]
     *
     * @var RendererInterface|null
     */
    protected $_renderer;

    /**
     * Retrieves the renderer associated with this instance.
     *
     * @since [*next-version*]
     *
     * @return RendererInterface|null The renderer.
     */
    protected function _getRenderer()
    {
        return $this->_renderer;
    }

    /**
     * Sets the renderer for this instance.
     *
     * @since [*next-version*]
     *
     * @param RendererInterface|null $renderer The renderer instance, or null.
     */
    protected function _setRenderer($renderer)
    {
        if ($renderer !== null && !($renderer instanceof RendererInterface)) {
            throw $this->_createInvalidArgumentException(
                $this->__('Invalid renderer'),
                null,
                null,
                $renderer
            );
        }

        $this->_renderer = $renderer;
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
