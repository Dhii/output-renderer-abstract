<?php

namespace Dhii\Output;

use Exception as RootException;

/**
 * Functionality for template setting and retrieval.
 *
 * @since [*next-version*]
 */
trait TemplateAwareTrait
{
    /**
     * The template.
     *
     * @since [*next-version*]
     *
     * @var TemplateInterface|null
     */
    protected $template;

    /**
     * Retrieves the template associated with this instance.
     *
     * @since [*next-version*]
     *
     * @return TemplateInterface|null The template.
     */
    protected function _getTemplate()
    {
        return $this->template;
    }

    /**
     * Assigns the template to this instance..
     *
     * @since [*next-version*]
     *
     * @param TemplateInterface|null $template The template.
     */
    protected function _setTemplate($template)
    {
        if ($template !== null && !($template instanceof TemplateInterface)) {
            throw $this->_createInvalidArgumentException($this->__('Invalid template'), null, null, $template);
        }

        $this->template = $template;
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
