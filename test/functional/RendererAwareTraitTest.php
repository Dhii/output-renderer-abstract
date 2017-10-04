<?php

namespace Dhii\Output\FuncTest;

use Dhii\Output\RendererInterface;
use Xpmock\TestCase;
use Dhii\Output\RendererAwareTrait as TestSubject;

/**
 * Tests {@see TestSubject}.
 *
 * @since 0.1
 */
class RendererAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since 0.1
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Output\RendererAwareTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since 0.1
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
     * @since 0.1
     *
     * @param string $output The render output of the renderer.
     *
     * @return RendererInterface The created renderer.
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
     * @since 0.1
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
     * Tests the renderer getter and setter methods to ensure correct assignment and retrieval.
     *
     * @since 0.1
     */
    public function testGetSetRenderer()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $reflect->_setRenderer($renderer = $this->createRenderer());

        $this->assertSame($renderer, $reflect->_getRenderer(), 'Set and retrieved renderers are not the same.');
    }
}
