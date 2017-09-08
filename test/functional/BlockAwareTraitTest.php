<?php

namespace Dhii\Output\FuncTest;

use Dhii\Output\BlockInterface;
use Xpmock\TestCase;
use Dhii\Output\BlockAwareTrait as TestSubject;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class BlockAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Output\BlockAwareTrait';

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
     * Creates a new mocked block instance for testing purposes.
     *
     * @since [*next-version*]
     *
     * @param string $output The render output of the block.
     *
     * @return BlockInterface The created block.
     */
    public function createBlock($output = '')
    {
        $mock = $this->mock('Dhii\Output\BlockInterface')
            ->render($output)
            ->__toString($output);

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
     * Tests the block getter and setter methods to ensure correct assignment and retrieval.
     *
     * @since [*next-version*]
     */
    public function testGetSetBlock()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $reflect->_setBlock($block = $this->createBlock());

        $this->assertSame($block, $reflect->_getBlock(), 'Set and retrieved blocks are not the same.');
    }
}
