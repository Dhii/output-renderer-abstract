<?php

namespace Dhii\Output\UnitTest;

use Exception;
use PHPUnit_Framework_MockObject_MockObject;
use Dhii\Util\String\StringableInterface as Stringable;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since 0.1
 */
class StringableRenderCatcherTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since 0.1
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Output\StringableRenderCatcherTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param array $methods The methods to mock.
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function createInstance($methods = [])
    {
        $methods = $this->mergeValues($methods, []);
        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
                ->setMethods($methods)
                ->getMockForTrait();

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
     * Creates a mocked exception instance for testing purposes.
     *
     * @since 0.1
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
     * Creates a stringable object that represents a given string.
     *
     * @since 0.1
     *
     * @param string $string The string for the stringable to represents.
     *
     * @return Stringable The stringable.
     */
    public function createStringable($string = null)
    {
        $mock = $this->mock('Dhii\Util\String\StringableInterface')
                ->__toString((string) $string)
                ->new();

        return $mock;
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
            'A valid instance of the test subject could not be created.'
        );
    }

    /**
     * Tests the `__toString()` method to ensure that the output is correct.
     *
     * @since 0.1
     */
    public function testToStringNormal()
    {
        $output = uniqid('test-output-');
        $subject = $this->createInstance();
        // Expect the render method to be called once
        $subject->expects($this->once())
                ->method('_render')
                ->willReturn($this->createStringable($output));

        $result = $subject->__toString();
        $this->assertInternalType('string', $result, 'Stringification must result in a primitive string');
        $this->assertEquals($output, $result, 'Expected and received output do not match');
    }

    /**
     * Tests the `__toString()` method to ensure that internal exceptions are handled and some output is still provided.
     *
     * @since 0.1
     */
    public function testToStringOnException()
    {
        $outputException = uniqid('test-output-');
        $subject = $this->createInstance(['_render', '_renderException']);
        // Expect the render method to be called once
        $subject->expects($this->once())
                ->method('_render')
                ->willThrowException($exception = $this->createException(uniqid('rendering-exception')));
        // Expect exception handler to called once with the exception as argument
        $subject->expects($this->once())
                ->method('_renderException')
                ->with($exception)
                ->willReturn($this->createStringable($outputException));

        $result = $subject->__toString();
        $this->assertInternalType('string', $result, 'Stringification must result in a primitive string');
        $this->assertEquals(
            $outputException,
            $result,
            'Expected and received output do not match when an exception is thrown internally.'
        );
    }

    /**
     * Tests whether the test subject can be cast into a string.
     *
     * @since [*next-version*]
     */
    public function testCanBeCastToString()
    {
        $rendered = uniqid('rendered');
        $subject = $this->createInstance(['_render']);
        // Expect the render method to be called once - the output itself is not relevant for this test
        $subject->expects($this->once())
                ->method('_render')
                ->willReturn($rendered);

        $result = (string) $subject;
        $this->assertSame($rendered, $result, 'Could not correctly cast to string');
    }

    /**
     * Tests whether the test subject can be cast into a string when an exception is thrown internally.
     *
     * @since [*next-version*]
     */
    public function testCanBeCastToStringOnException()
    {
        $exception = $this->createException('rendering-failed');
        $subject = $this->createInstance(['_render', '_renderException']);
        // Expect the render method to be called once
        $subject->expects($this->once())
                ->method('_render')
                ->willThrowException($exception);
        // Mock render-on-exception method
        $subject->method('_renderException')
                ->with($exception)
                ->willReturn($outputException = 'Whoops. An error occurred.');

        $this->assertInternalType(
            'string',
            (string) $subject,
            'Test subject could not be casted to string when an exception is thrown internally.'
        );
    }
}
