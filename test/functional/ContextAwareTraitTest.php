<?php

namespace Dhii\Output\FuncTest;

use InvalidArgumentException;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Psr\Container\ContainerInterface;
use Xpmock\TestCase;
use Dhii\Output\ContextAwareTrait as TestSubject;

/**
 * Tests {@see TestSubject}.
 *
 * @since 0.1
 */
class ContextAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since 0.1
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Output\ContextAwareTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since 0.1
     *
     * @return MockObject
     */
    public function createInstance()
    {
        // Create mock
        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
                     ->setMethods(['_normalizeContainer'])
                     ->getMockForTrait();

        return $mock;
    }

    /**
     * Creates a new container for values.
     *
     * @since 0.1
     *
     * @param array $data The map of values.
     *
     * @return ContainerInterface The created context.
     */
    public function createContext($data = [])
    {
        $mock = $this->mock('Psr\Container\ContainerInterface')
                     ->get(
                         function($key) use ($data) {
                             return isset($data[$key]) ? $data[$key] : null;
                         }
                     )
                     ->has();

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
     * Tests the context getter and setter methods to ensure correct assignment and retrieval.
     *
     * @since 0.1
     */
    public function testGetSetContext()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $subject->method('_normalizeContainer')->willReturnArgument(0);

        $ctx = $this->createContext();
        $reflect->_setContext($ctx);

        $this->assertSame($ctx, $reflect->_getContext(), 'Set and retrieved blocks are not the same.');
    }

    /**
     * Tests the context getter and setter methods with a null context to ensure correct assignment and retrieval.
     *
     * @since 0.1
     */
    public function testGetSetContextNull()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $subject->expects($this->never())->method('_normalizeContainer');

        $reflect->_setContext(null);

        $this->assertNull($reflect->_getContext(), 'Retrieved context is not null.');
    }

    /**
     * Tests the context setter methods to assert that an exception is thrown when the internal normalization fails.
     *
     * @since 0.1
     */
    public function testSetContextInvalid()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $subject->method('_normalizeContainer')->willThrowException(new InvalidArgumentException());

        $this->setExpectedException('InvalidArgumentException');

        $reflect->_setContext("invalid");
    }
}
