<?php

namespace Dhii\Output\UnitTest;

use InvalidArgumentException;
use Dhii\Validation\Exception\ValidationFailedException;
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
     * Creates a validation failed exception for testing purposes.
     *
     * @since 0.1
     *
     * @return ValidationFailedException
     */
    public function createValidationFailedException()
    {
        $mock = $this->getMock('Dhii\Validation\Exception\ValidationFailedException');

        return $mock;
    }

    /**
     * Creates a mocked invalid argument exception for testing purposes.
     *
     * @since 0.1
     *
     * @param string         $message  The message.
     * @param int|null       $code     The code.
     * @param Exception|null $previous The previous exception.
     * @param mixed|null     $argument The argument.
     *
     * @return InvalidArgumentException The new exception.
     */
    public function createInvalidArgumentException(
        $message = '',
        $code = 0,
        $previous = null,
        $argument = null
    ) {
        $mock = $this->mock('InvalidArgumentException')
                     ->getMessage($message)
                     ->getCode($code)
                     ->getPrevious($previous)
                     ->getArgument($argument)
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
        $me = $this;

        $subject->expects($this->once())
                ->method('_validateContext')
                ->willThrowException($vfException = $this->createValidationFailedException());

        $subject->expects($this->once())
                ->method('_createInvalidArgumentException')
                ->willReturnCallback(function ($message = null, $code = null, $previous = null, $arg = null) use ($me) {
                    return $me->createInvalidArgumentException($message, $code, $previous, $arg);
                });

        $this->setExpectedException('InvalidArgumentException');

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
