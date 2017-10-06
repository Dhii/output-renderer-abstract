<?php

namespace Dhii\Output\UnitTest;

use Dhii\Output\RendererInterface;
use Dhii\Validation\Exception\ValidationFailedExceptionInterface;
use Exception;
use PHPUnit_Framework_MockObject_MockObject;
use ReflectionMethod;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since 0.1
 */
class AbstractTemplateTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since 0.1
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Output\AbstractTemplate';

    /**
     * Creates a new instance of the test subject.
     *
     * @since 0.1
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function createInstance()
    {
        $mock = $this->getMockForAbstractClass(static::TEST_SUBJECT_CLASSNAME);

        return $mock;
    }

    /**
     * Creates a mock that both extends a class and implements interfaces.
     *
     * This is particularly useful for cases where the mock is based on an
     * internal class, such as in the case with exceptions. Helps to avoid
     * writing hard-coded stubs.
     *
     * @since 0.1
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
     * Creates a validation failed exception for testing purposes.
     *
     * @since 0.1
     *
     * @return ValidationFailedExceptionInterface
     */
    public function createValidationFailedException()
    {
        $mock = $this->mockClassAndInterfaces('Exception', ['Dhii\Validation\Exception\ValidationFailedExceptionInterface']);
        $mock->method('getValidationErrors')
                ->will($this->returnValue(null));
        $mock->method('getValidator')
                ->will($this->returnValue(null));
        $mock->method('getSubject')
                ->will($this->returnValue(null));

        $mock->method('getMessage')
                ->will($this->returnValue(null));
        $mock->method('getFile')
                ->will($this->returnValue(null));
        $mock->method('getLine')
                ->will($this->returnValue(null));
        $mock->method('getPrevious')
                ->will($this->returnValue(null));
        $mock->method('getCode')
                ->will($this->returnValue(null));
        $mock->method('getTrace')
                ->will($this->returnValue(null));
        $mock->method('getTraceAsString')
                ->will($this->returnValue(null));
        $mock->method('__toString')
                ->will($this->returnValue(null));

        return $mock;
    }

    /**
     * Creates a mocked context renderer exception for testing purposes.
     *
     * @since 0.1
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
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since 0.1
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
     * @since 0.1
     */
    public function testRender()
    {
        $subject = $this->getMockForAbstractClass(static::TEST_SUBJECT_CLASSNAME);

        $subject->expects($this->once())
                ->method('_renderWithContext')
                ->willReturn($output = 'Some testing rendering output');

        $method = new ReflectionMethod(static::TEST_SUBJECT_CLASSNAME, '_render');
        $method->setAccessible(true);
        $result = $method->invoke($subject);

        $this->assertEquals($output, $result, 'Expected and received output do not match');
    }

    /**
     * Tests the rendering with context validation failure to assert that an exception is thrown.
     *
     * @since 0.1
     */
    public function testRenderContextInvalid()
    {
        $subject = $this->getMockForAbstractClass(static::TEST_SUBJECT_CLASSNAME);

        $crException = $this->mock('Exception')
            ->getMessage(uniqid('invalid-ctx-'))
            ->getCode(rand(0, 10))
            ->new();

        $subject->expects($this->once())
                ->method('_validateContext')
                ->willThrowException($vfException = $this->createValidationFailedException());

        $subject->expects($this->once())
                ->method('_createTemplateException')
                ->willReturn($crException);

        $this->setExpectedException('Exception');

        $method = new ReflectionMethod(static::TEST_SUBJECT_CLASSNAME, '_render');
        $method->setAccessible(true);
        $method->invoke($subject);
    }

    /**
     * Tests the rendering to ensure that context normalization occurs and that the abstracted render method gets
     * invoked with the normalized context as argument.
     *
     * @since 0.1
     */
    public function testRenderNormalizeContext()
    {
        $subject = $this->getMockForAbstractClass(static::TEST_SUBJECT_CLASSNAME);

        $subject->expects($this->once())
            ->method('_normalizeContext')
            ->willReturn($normalizedCtx = ['some' => 'context', 'foo' => 'bar']);

        $subject->expects($this->once())
            ->method('_renderWithContext')
            ->with($normalizedCtx);

        $method = new ReflectionMethod(static::TEST_SUBJECT_CLASSNAME, '_render');
        $method->setAccessible(true);
        $method->invoke($subject);
    }
}
