<?php

namespace Dhii\Output;

use Dhii\Output\Exception\TemplateRenderExceptionInterface;
use Dhii\Output\Exception\RendererExceptionInterface;
use Dhii\Util\String\StringableInterface as Stringable;
use Dhii\Validation\Exception\ValidationFailedExceptionInterface;
use Exception as RootException;

/**
 * Common abstract functionality for renderers that can render using an optional context.
 *
 * @since [*next-version*]
 */
abstract class AbstractTemplate
{
    /**
     * Produce output based on context.
     *
     * @since [*next-version*]
     *
     * @param mixed|null $context The context;
     *                            something that can provide more information on how to perform rendering.
     *
     * @throws TemplateRenderExceptionInterface If cannot render.
     * @throws RendererExceptionInterface       Any other problem related to the renderer.
     *
     * @return string|Stringable The output.
     */
    protected function _render($context = null)
    {
        try {
            $this->_validateContext($context);
        } catch (ValidationFailedExceptionInterface $exception) {
            throw $this->_createTemplateException(
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
     * @throws TemplateRenderExceptionInterface If cannot render.
     * @throws RendererExceptionInterface       Any other problem related to the renderer.
     *
     * @return string|Stringable The output.
     */
    abstract protected function _renderWithContext($context);

    /**
     * Creates a new template render failure exception.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable|null $message  The error message, if any.
     * @param int|null               $code     The error code, if any.
     * @param RootException|null     $previous The inner exception for chaining, if any.
     * @param mixed|null             $context  The context that was involved, if any.
     *
     * @return TemplateRenderExceptionInterface The new exception.
     */
    abstract protected function _createTemplateException(
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
     *
     * @param string $string  The format string to translate.
     * @param array  $args    Placeholder values to replace in the string.
     * @param mixed  $context The context for translation.
     *
     * @return string The translated string.
     */
    abstract protected function __($string, $args = [], $context = null);
}
