<?php

namespace Dhii\Output\FuncTest;

use Dhii\Output\ContextRendererInterface;
use InvalidArgumentException;
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
        $mock->method('__')->will($this->returnArgument(0));
        $mock->method('_createInvalidArgumentException')
                ->will($this->returnCallback(function ($message = null) {
                    return new InvalidArgumentException($message);
                }));

        return $mock;
    }

    /**
     * Creates a new context renderer instance.
     *
     * @since [*next-version*]
     *
     * @param string $output The render output of the renderer.
     *
     * @return ContextRendererInterface The new context renderer.
     */
    public function createContextRenderer($output = '')
    {
        $mock = $this->mock('Dhii\Output\ContextRendererInterface')
                     ->render($output);

        return $mock->new();
    }

    /**
     * Creates a new renderer instance.
     *
     * @since [*next-version*]
     *
     * @param string $output The render output of the renderer.
     *
     * @return ContextRendererInterface The new renderer.
     */
    public function createRenderer($output = '')
    {
        $mock = $this->mock('Dhii\Output\RendererInterface')
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

        $reflect->_setContextRenderer($renderer = $this->createContextRenderer());

        $this->assertSame(
            $renderer,
            $reflect->_getContextRenderer(),
            'Set and retrieved context renderers are not the same.'
        );
    }

    /**
     * Tests that an attempt to set an invalid context results in an appropriate exception being thrown.
     *
     * @since [*next-version*]
     */
    public function testGetSetContextRendererFailure()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);
        $data = $this->createRenderer(uniqid('test-output-'));

        $this->setExpectedException('InvalidArgumentException');
        $reflect->_setContextRenderer($data);
    }
}
