<?php

namespace Dhii\Output;

use Dhii\Util\String\StringableInterface as Stringable;
use Exception;

/**
 * Common abstract functionality for blocks.
 *
 * @since [*next-version*]
 */
abstract class AbstractBlock
{
    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     *
     * @return string
     */
    public function __toString()
    {
        try {
            return (string) $this->_render();
        } catch (Exception $exception) {
            return (string) $this->_renderOnException($exception);
        }
    }

    /**
     * Renders the block.
     *
     * @since [*next-version*]
     *
     * @return string|Stringable The output.
     */
    abstract protected function _render();

    /**
     * Produces output when an exception is thrown while rendering via {@see __toString}.
     *
     * @since [*next-version*]
     *
     * @param Exception $exception The exception that was thrown during rendering.
     *
     * @return string|Stringable The output.
     */
    abstract protected function _renderOnException(Exception $exception);
}
