<?php

namespace Dhii\Output;

use Dhii\Output\Exception\ContextRenderExceptionInterface;
use Dhii\Output\Exception\RendererExceptionInterface;
use Dhii\Util\String\StringableInterface as Stringable;
use Dhii\Validation\Exception\ValidationFailedExceptionInterface;
use Exception as RootException;

/**
 * Common abstract functionality for renderers that can render using an optional context.
 *
 * @since [*next-version*]
 */
abstract class AbstractContextRenderer
{
    /**
     * Produce output based on context.
     *
     * @since 0.1
     *
     * @param mixed|null $context The context;
     *                            something that can provide more information on how to perform rendering.
     *
     * @throws ContextRenderExceptionInterface If cannot render.
     * @throws RendererExceptionInterface      Any other problem related to the renderer.
     *
     * @return string|Stringable The output.
     */
    protected function _render($context = null)
    {
        try {
            $this->_validateContext($context);
        } catch (ValidationFailedExceptionInterface $exception) {
            throw $this->_createContextRendererException(
                $this->__('Given context is invalid'), null, $exception, $context
            );
        }

        $normalizedCtx = $this->_normalizeContext($context);

        return $this->_renderWithContext($normalizedCtx);
    }

    /**
     * Validates the context.
     *
     * @since [*next-version*]
     *
     * @param mixed $context The context to validate.
     *
     * @throws ValidationFailedExceptionInterface If the context is invalid.
     */
    abstract protected function _validateContext($context);

    /**
     * Normalizes the context into data usable by the renderer for producing output.
     *
     * @since [*next-version*]
     *
     * @param mixed $context The context to normalize.
     *
     * @return mixed The normalized context.
     */
    abstract protected function _normalizeContext($context);

    /**
     * Produces output using a given context.
     *
     * @since [*next-version*]
     *
     * @param mixed $context The validated and normalized context.
     *
     * @return string|Stringable The output.
     *
     * @throws ContextRenderExceptionInterface If cannot render.
     * @throws RendererExceptionInterface      Any other problem related to the renderer.
     */
    abstract protected function _renderWithContext($context);

    /**
     * Creates a new context render failure exception.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable|null $message  The error message, if any.
     * @param int|null               $code     The error code, if any.
     * @param RootException|null     $previous The inner exception for chaining, if any.
     * @param mixed|null             $context  The context that was involved, if any.
     *
     * @return ContextRenderExceptionInterface The new exception.
     */
    abstract protected function _createContextRendererException(
        $message = null,
        $code = null,
        RootException $previous = null,
        $context = null
    );
    /**
     * Translates a string, and replaces placeholders.
     *
     * @since [*next-version*]
     * @see sprintf()
     * @see _translate()
     *
     * @param string $string  The format string to translate.
     * @param array  $args    Placeholder values to replace in the string.
     * @param mixed  $context The context for translation.
     *
     * @return string The translated string.
     */
    abstract protected function __($string, $args = [], $context = null);
}
