<?php

namespace Dhii\Output\FuncTest;

use Psr\Container\ContainerInterface;
use Xpmock\TestCase;
use Dhii\Output\ContextAwareTrait as TestSubject;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class ContextAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Output\ContextAwareTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
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
     * Creates a new container for values.
     *
     * @since [*next-version*]
     *
     * @param array $data The map of values.
     *
     * @return ContainerInterface The created context.
     */
    public function createContext($data = [])
    {
        $mock = $this->mock('Psr\Container\ContainerInterface')
            ->get(function ($key) use ($data) {
                return isset($data[$key]) ? $data[$key] : null;
            })
            ->has();

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

        $this->assertInternalType(
            'object',
            $subject,
            'An instance of the test subject could not be created'
        );
    }

    /**
     * Tests the context getter and setter methods to ensure correct assignment and retrieval.
     *
     * @since [*next-version*]
     */
    public function testGetSetContext()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $reflect->_setContext($block = $this->createContext());

        $this->assertSame($block, $reflect->_getContext(), 'Set and retrieved blocks are not the same.');
    }
}
