<?php

namespace Dhii\Output;

use Exception as RootException;
use Dhii\Output\Exception\CouldNotRenderExceptionInterface;
use Dhii\Output\Exception\RendererExceptionInterface;
use Psr\Container\ContainerInterface;
use Dhii\Util\String\StringableInterface as Stringable;

/**
 * Common functionality for template rendering.
 *
 * @since 0.1
 */
abstract class AbstractTemplateBlock extends AbstractBlock
{
    /**
     * Retrieves the template associated with this instance.
     *
     * @since 0.1
     *
     * @return TemplateInterface|null The template.
     */
    abstract protected function _getTemplate();

    /**
     * Retrieves a rendering context.
     *
     * @param TemplateInterface $template The template, for which to get the context.
     *
     * @since 0.1
     *
     * @return ContainerInterface The context.
     */
    abstract protected function _getContextFor(TemplateInterface $template);

    /**
     * Creates a new render failure exception.
     *
     * @since 0.1
     *
     * @param string|Stringable|null $message  The error message, if any.
     * @param int|null               $code     The error code, if any.
     * @param RootException|null     $previous The inner exception for chaining, if any.
     *
     * @return CouldNotRenderExceptionInterface The new exception.
     */
    abstract protected function _createCouldNotRenderException(
        $message = null,
        $code = null,
        RootException $previous = null
    );

    /**
     * Creates a new render-related exception.
     *
     * @since 0.1
     *
     * @param string|Stringable|null $message  The error message, if any.
     * @param int|null               $code     The error code, if any.
     * @param RootException|null     $previous The inner exception for chaining, if any.
     *
     * @return RendererExceptionInterface The new exception.
     */
    abstract protected function _createRendererException(
        $message = null,
        $code = null,
        RootException $previous = null
    );

    /**
     * Translates a string, and replaces placeholders.
     *
     * @since 0.1
     * @see sprintf()
     *
     * @param string $string  The format string to translate.
     * @param array  $args    Placeholder values to replace in the string.
     * @param mixed  $context The context for translation.
     *
     * @return string The translated string.
     */
    abstract protected function __($string, $args = [], $context = null);

    /**
     * {@inheritdoc}
     *
     * Uses a template.
     *
     * @since 0.1
     */
    protected function _render()
    {
        $template = $this->_getTemplate();
        if (!($template instanceof TemplateInterface)) {
            throw $this->_createRendererException($this->__('A valid template could not be retrieved'));
        }

        $context = $this->_getContextFor($template);

        try {
            return $this->_renderTemplate($template, $context);
        } catch (RendererExceptionInterface $e) {
            throw $this->_createCouldNotRenderException($this->__('Could not render template'), null, $e);
        }
    }

    /**
     * Renders a template with context.
     *
     * @param TemplateInterface       $template The template to render.
     * @param ContainerInterface|null $context  The context to use for rendering.
     *
     * @since 0.1
     *
     * @throws TemplateRenderExceptionInterface The template may throw this if a problem occurs.
     * @throws RendererExceptionInterface       The template may throw this if a problem specific to rendering occurs.
     *
     * @return string|Stringable The rendered output.
     */
    abstract protected function _renderTemplate(TemplateInterface $template, ContainerInterface $context = null);
}
