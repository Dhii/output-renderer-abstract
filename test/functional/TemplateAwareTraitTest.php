<?php

namespace Dhii\Output\FuncTest;

use Dhii\Output\TemplateInterface;
use InvalidArgumentException;
use Dhii\Output\RendererAwareTrait as TestSubject;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since 0.1
 */
class TemplateAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since 0.1
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Output\TemplateAwareTrait';

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
     * @since 0.1
     *
     * @param string $output The render output of the renderer.
     *
     * @return TemplateInterface The new context renderer.
     */
    public function createTemplate($output = '')
    {
        $mock = $this->mock('Dhii\Output\TemplateInterface')
                     ->render($output);

        return $mock->new();
    }

    /**
     * Creates a new renderer instance.
     *
     * @since 0.1
     *
     * @param string $output The render output of the renderer.
     *
     * @return TemplateInterface The new renderer.
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
     * Tests the template getter and setter methods to ensure correct assignment and retrieval.
     *
     * @since 0.1
     */
    public function testGetSetTemplate()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $reflect->_setTemplate($renderer = $this->createTemplate());

        $this->assertSame(
            $renderer,
            $reflect->_getTemplate(),
            'Set and retrieved context renderers are not the same.'
        );
    }

    /**
     * Tests that an attempt to set an invalid template results in an appropriate exception being thrown.
     *
     * @since 0.1
     */
    public function testGetSetTemplateFailure()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);
        $data = $this->createRenderer(uniqid('test-output-'));

        $this->setExpectedException('InvalidArgumentException');
        $reflect->_setTemplate($data);
    }
}
