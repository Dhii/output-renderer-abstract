<?php

namespace Dhii\Output\FuncTest;

use Dhii\Output\ContextRendererInterface;
use Dhii\Output\RendererAwareTrait as TestSubject;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class ContextRendererAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Output\ContextRendererAwareTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return TestSubject
     */
    public function createInstance()
    {
        // Create mock
        $mock = $this->getMockForTrait(static::TEST_SUBJECT_CLASSNAME);

        return $mock;
    }

    /**
     * Creates a new mocked renderer instance for testing purposes.
     *
     * @since [*next-version*]
     *
     * @param string $output The render output of the renderer.
     *
     * @return ContextRendererInterface The created renderer.
     */
    public function createRenderer($output = '')
    {
        $mock = $this->mock('Dhii\Output\ContextRendererInterface')
                     ->render($output);

        return $mock->new();
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInternalType(
            'object',
            $subject,
            'An instance of the test subject could not be created'
        );
    }

    /**
     * Tests the context renderer getter and setter methods to ensure correct assignment and retrieval.
     *
     * @since [*next-version*]
     */
    public function testGetSetContextRenderer()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $reflect->_setContextRenderer($renderer = $this->createRenderer());

        $this->assertSame(
            $renderer,
            $reflect->_getContextRenderer(),
            'Set and retrieved context renderers are not the same.'
        );
    }
}
