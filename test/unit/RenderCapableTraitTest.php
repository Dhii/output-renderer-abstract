<?php

namespace Dhii\Output\UnitTest;

use Dhii\Output\RendererInterface;
use Dhii\Output\Exception\CouldNotRenderExceptionInterface;
use Dhii\Output\Exception\RendererExceptionInterface;
use Exception as RootException;
use Xpmock\TestCase;
use Dhii\Output\RenderCapableTrait as TestSubject;
use Dhii\Output\TemplateInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class RenderCapableTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Output\RenderCapableTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param array $methods The methods to mock.
     *
     * @return MockObject The new instance.
     */
    public function createInstance($methods = [])
    {
        $methods = $this->mergeValues($methods, [
            '__',
        ]);

        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
            ->setMethods($methods)
            ->getMockForTrait();

        $mock->method('__')
                ->will($this->returnArgument(0));

        return $mock;
    }

    /**
     * Merges the values of two arrays.
     *
     * The resulting product will be a numeric array where the values of both inputs are present, without duplicates.
     *
     * @since [*next-version*]
     *
     * @param array $destination The base array.
     * @param array $source      The array with more keys.
     *
     * @return array The array which contains unique values
     */
    public function mergeValues($destination, $source)
    {
        return array_keys(array_merge(array_flip($destination), array_flip($source)));
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
     * @param string $className      Name of the class for the mock to extend.
     * @param string $interfaceNames Names of the interfaces for the mock to implement.
     *
     * @return object The object that extends and implements the specified class and interfaces.
     */
    public function mockClassAndInterfaces($className, $interfaceNames = [])
    {
        $paddingClassName = uniqid($className);
        $definition = vsprintf('abstract class %1$s extends %2$s implements %3$s {}', [
            $paddingClassName,
            $className,
            implode(', ', $interfaceNames),
        ]);
        eval($definition);

        return $this->getMockForAbstractClass($paddingClassName);
    }

    /**
     * Creates a new exception.
     *
     * @since [*next-version*]
     *
     * @param string $message The exception message.
     *
     * @return RootException The new exception.
     */
    public function createException($message = '')
    {
        $mock = $this->getMockBuilder('Exception')
            ->setConstructorArgs([$message])
            ->getMock();

        return $mock;
    }

    /**
     * Creates a validation failed exception.
     *
     * @since [*next-version*]
     *
     * @param string             $message  The error message, if any.
     * @param int                $code     The error code, if any.
     * @param RootException|null $previous The inner exception, if any.
     * @param RendererInterface  $renderer The faulty renderer, if any.
     *
     * @return CouldNotRenderExceptionInterface The new exception
     */
    public function createCouldNotRenderException($message = '', $code = 0, $previous = null, $renderer = null)
    {
        $mock = $this->mockClassAndInterfaces('Exception', ['Dhii\Output\Exception\CouldNotRenderExceptionInterface']);
        $mock->method('getMessage')
            ->will($this->returnValue($message));
        $mock->method('getCode')
            ->will($this->returnValue($code));
        $mock->method('getPrevious')
            ->will($this->returnValue($previous));
        $mock->method('getRenderer')
                ->will($this->returnValue($renderer));

        return $mock;
    }

    /**
     * Creates a new template.
     *
     * @since [*next-version*]
     *
     * @param string $content The content of the template.
     *
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

        $this->assertInternalType(
            'object',
            $subject,
            'A valid instance of the test subject could not be created.'
        );
    }

    /**
     * Tests the rendering to ensure that the appropriate internal methods are called and that correct output is returned.
     *
     * @since [*next-version*]
     */
    public function testRender()
    {
        $template = $this->createTemplate();
        $subject = $this->createInstance(['_getTemplate', '_getContextFor', '_renderTemplate']);
        $context = array_fill(0, rand(1, 9), uniqid('value'));
        $output = uniqid('rendered');
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_getTemplate')
            ->will($this->returnValue($template));
        $subject->expects($this->exactly(1))
            ->method('_getContextFor')
            ->with($template)
            ->will($this->returnValue($context));
        $subject->expects($this->exactly(1))
            ->method('_renderTemplate')
            ->with($template, $context)
            ->will($this->returnValue($output));

        $result = $_subject->_render();
        $this->assertEquals($output, $result, 'Rendering did not produce desired result');
    }

    /**
     * Tests the rendering to ensure that the appropriate internal methods are called and that correct output is returned.
     *
     * @since [*next-version*]
     */
    public function testRenderFailure()
    {
        $output = uniqid('rendered');
        $exception = $this->createException(uniqid('previous'));
        $subject = $this->createInstance(['_getTemplate', '_getContextFor', '_renderTemplate']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_getTemplate')
            ->will($this->throwException($exception));
        $subject->method('_throwCouldNotRenderException')
            ->will($this->returnCallback(function ($message = '', $code = 0, $previous = null) {
                throw $this->createCouldNotRenderException($message, $code, $previous);
            }));

        $this->setExpectedException('Dhii\Output\Exception\CouldNotRenderExceptionInterface');
        $_subject->_render();
    }
}
