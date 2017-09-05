<?php

namespace Dhii\Output\UnitTest;

use Exception;
use PHPUnit_Framework_MockObject_MockObject;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class AbstractBlockTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Output\AbstractBlock';

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
        $mock->method('_render')->willReturn('');
        $mock->method('_renderOnException')->willReturn('');

        return $mock;
    }

    /**
     * Creates a mocked exception instance for testing purposes.
     *
     * @since [*next-version*]
     *
     * @param string         $message  The exception error message.
     * @param int|null       $code     The exception code.
     * @param Exception|null $previous The previous exception in the chain.
     *
     * @return Exception The created exception.
     */
    public function createException($message, $code = null, $previous = null)
    {
        $mock = $this->mock('\Exception')
                     ->getMessage($message)
                     ->getCode($code)
                     ->getPrevious($previous);

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
     * Tests the __toString method to ensure that the output is correct.
     *
     * @since [*next-version*]
     */
    public function testToString()
    {
        $subject = $this->getMockForAbstractClass(static::TEST_SUBJECT_CLASSNAME);
        // Expect the render method to be called once
        $subject->expects($this->once())
                ->method('_render')
                ->willReturn($output = 'Some testing render output');
        // Mock render-on-exception method
        $subject->method('_renderOnException')
                ->willReturn($outputException = 'Whoops. An error occurred.');

        $this->assertEquals($output, $subject->__toString(), 'Expected and received output do not match');
    }

    /**
     * Tests the __toString method to ensure that internal exceptions are handled and some output is still provided.
     *
     * @since [*next-version*]
     */
    public function testToStringOnException()
    {
        $subject = $this->getMockForAbstractClass(static::TEST_SUBJECT_CLASSNAME);
        // Expect the render method to be called once
        $subject->expects($this->once())
                ->method('_render')
                ->willThrowException($exception = $this->createException('A wild exception appears!'));
        // Expect exception handler to called once with the exception as argument
        $subject->expects($this->once())
                ->method('_renderOnException')
                ->with($exception)
                ->willReturn($outputException = 'Whoops. An error occurred.');

        $this->assertEquals(
            $outputException,
            $subject->__toString(),
            'Expected and received output do not match when an exception is thrown internally.'
        );
    }

    /**
     * Tests whether the test subject can be casted into a string.
     *
     * @since [*next-version*]
     */
    public function testCanBeCastedToString()
    {
        $subject = $this->getMockForAbstractClass(static::TEST_SUBJECT_CLASSNAME);
        // Expect the render method to be called once - the output itself is not relevant for this test
        $subject->expects($this->once())
                ->method('_render')
                ->willReturn($expected = '');
        $subject->method('_renderOnException')
                ->willReturn(null);

        $this->assertInternalType(
            'string',
            (string) $subject,
            'Test subject could not be casted to string'
        );
    }

    /**
     * Tests whether the test subject can be casted into a string when an exception is thrown internally.
     *
     * @since [*next-version*]
     */
    public function testCanBeCastedToStringOnException()
    {
        $subject = $this->getMockForAbstractClass(static::TEST_SUBJECT_CLASSNAME);
        // Expect the render method to be called once
        $subject->expects($this->once())
                ->method('_render')
                ->willThrowException($this->createException('A wild exception appears!'));
        // Mock render-on-exception method
        $subject->method('_renderOnException')
                ->willReturn($outputException = 'Whoops. An error occurred.');

        $this->assertInternalType(
            'string',
            (string) $subject,
            'Test subject could not be casted to string when an exception is thrown internally.'
        );
    }
}
