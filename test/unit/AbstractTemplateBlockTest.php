<?php

namespace Dhii\Output\UnitTest;

use Dhii\Output\RendererInterface;
use Dhii\Output\Exception\CouldNotRenderExceptionInterface;
use Dhii\Output\Exception\RendererExceptionInterface;
use Exception;
use Xpmock\TestCase;
use Dhii\Output\AbstractTemplateBlock as TestSubject;
use Dhii\Output\TemplateInterface;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class AbstractTemplateBlockTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Output\AbstractTemplateBlock';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return TestSubject The new instance.
     */
    public function createInstance($template = null, $context = null)
    {
        $me = $this;
        $mock = $this->getMock(static::TEST_SUBJECT_CLASSNAME);
        $mock->method('_getTemplate')
                ->will($this->returnValue($template));
        $mock->method('_getContextFor')
                ->will($this->returnValue($context));
        $mock->method('_createCouldNotRenderException')
                ->will($this->returnCallback(function($message) use (&$me, $mock) {
                    return $me->createCouldNotRenderException($message, $mock);
                }));
        $mock->method('_createRendererException')
                ->will($this->returnCallback(function($message) use (&$me, $mock) {
                    return $me->createRendererException($message, $mock);
                }));
        $mock->method('__')
                ->will($this->returnArgument(0));

        return $mock;
    }

    /**
     * Creates a mock that both extends a class and implements interfaces.
     *
     * This is particularly useful for cases where the mock is based on an
     * internal class, such as in the case with exceptions. Helps to avoid
     * writing hard-coded stubs.
     *
     * @since [*next-version*]
     *
     * @param string $className Name of the class for the mock to extend.
     * @param string $interfaceNames Names of the interfaces for the mock to implement.
     * @return object The object that extends and implements the specified class and interfaces.
     */
    public function mockClassAndInterfaces($className, $interfaceNames = [])
    {
        $paddingClassName = uniqid($className);
        $definition = vsprintf('abstract class %1$s extends %2$s implements %3$s {}', [
            $paddingClassName,
            $className,
            implode(', ' , $interfaceNames),
        ]);
        eval($definition);

        return $this->getMockForAbstractClass($paddingClassName);
    }

    /**
     * Creates a validation failed exception.
     *
     * @since [*next-version*]
     *
     * @return CouldNotRenderExceptionInterface The new exception
     */
    public function createCouldNotRenderException($message = '', $renderer = null)
    {
        $mock = $this->mockClassAndInterfaces('Exception', ['Dhii\Output\Exception\CouldNotRenderExceptionInterface']);
        $mock->method('getMessage')
                ->will($this->returnValue($message));
        $mock->method('getRenderer')
                ->will($this->returnValue($renderer));

        return $mock;
    }

    /**
     * Creates a validation failed exception.
     *
     * @since [*next-version*]
     *
     * @return RendererExceptionInterface The new exception
     */
    public function createRendererException($message = '', $renderer = null)
    {
        $mock = $this->mockClassAndInterfaces('Exception', ['Dhii\Output\Exception\RendererExceptionInterface']);
        $mock->method('getMessage')
                ->will($this->returnValue($message));
        $mock->method('getRenderer')
                ->will($this->returnValue($renderer));

        return $mock;
    }

    /**
     * Creates a mocked context renderer exception.
     *
     * @since [*next-version*]
     *
     * @param string                 $message  The message.
     * @param int|null               $code     The code.
     * @param Exception|null         $previous The previous exception.
     * @param RendererInterface|null $renderer The renderer.
     * @param mixed|null             $context  The context.
     *
     * @return mixed
     */
    public function createTemplateException(
        $message = '',
        $code = 0,
        $previous = null,
        $renderer = null,
        $context = null
    ) {
        $mock = $this->mock('Dhii\Output\Exception\TemplateRenderExceptionInterface')
                     ->getMessage($message)
                     ->getCode($code)
                     ->getPrevious($previous)
                     ->getRenderer($renderer)
                     ->getContext($context)
                     ->getLine()
                     ->getFile()
                     ->getTrace()
                     ->getTraceAsString()
                     ->__toString();

        return $mock->new();
    }

    /**
     * Creates a new template.
     *
     * @since [*next-version*]
     *
     * @param string $content The content of the template.
     * @return TemplateInterface The template.
     */
    public function createTemplate($content = '')
    {
        return $this->mock('Dhii\Output\TemplateInterface')
                ->render(function ($context) use ($content) {
                    return vsprintf($content, $context);
                })
                ->new();
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInstanceOf(
            static::TEST_SUBJECT_CLASSNAME,
            $subject,
            'A valid instance of the test subject could not be created.'
        );
    }

    /**
     * Tests the rendering to ensure that the appropriate internal methods are called and that output can be produced.
     *
     * @since [*next-version*]
     */
    public function testRender()
    {
        $template = $this->createTemplate();
        $subject = $this->createInstance($template);
        $_subject = $this->reflect($subject);
        $subject->expects($this->once())
                ->method('_renderTemplate');

        $_subject->_render();
    }
}
