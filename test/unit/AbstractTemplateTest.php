<?php

namespace Dhii\Output\UnitTest;

use Dhii\Output\RendererInterface;
use Dhii\Validation\Exception\ValidationFailedException;
use Exception;
use PHPUnit_Framework_MockObject_MockObject;
use ReflectionMethod;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class AbstractTemplateTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Output\AbstractTemplate';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function createInstance()
    {
        $mock = $this->getMockForAbstractClass(static::TEST_SUBJECT_CLASSNAME);

        return $mock;
    }

    /**
     * Creates a validation failed exception for testing purposes.
     *
     * @since [*next-version*]
     *
     * @return ValidationFailedException
     */
    public function createValidationFailedException()
    {
        $mock = $this->getMock('Dhii\Validation\Exception\ValidationFailedException');

        return $mock;
    }

    /**
     * Creates a mocked context renderer exception for testing purposes.
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
     * @since [*next-version*]
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
     * @since [*next-version*]
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
