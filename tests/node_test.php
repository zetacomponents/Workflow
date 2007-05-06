<?php
/**
 * @package Workflow
 * @subpackage Tests
 * @version //autogentag//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

require_once 'case.php';

/**
 * @package Workflow
 * @subpackage Tests
 */
class ezcWorkflowNodeTest extends ezcWorkflowTestCase
{
    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( 'ezcWorkflowNodeTest' );
    }

    public function testActionClassNotFound()
    {
        $action = new ezcWorkflowNodeAction( 'NotExistingClass' );
        $this->assertEquals( 'Class not found.', (string)$action );
    }

    public function testActionClassNotServiceObject()
    {
        $action = new ezcWorkflowNodeAction( 'StdClass' );
        $this->assertEquals( 'Class does not implement the ezcWorkflowServiceObject interface.', (string)$action );
    }

    public function testInputConstructor()
    {
        try
        {
            $input = new ezcWorkflowNodeInput( null );
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }

        $this->fail();
    }

    public function testInputConstructor2()
    {
        try
        {
            $input = new ezcWorkflowNodeInput( array( 'foo' => new StdClass ) );
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }

        $this->fail();
    }

    public function testInputConstructor3()
    {
        try
        {
            $input = new ezcWorkflowNodeInput( array( new StdClass ) );
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }

        $this->fail();
    }

    public function testVariableSetConstructor()
    {
        try
        {
            $set = new ezcWorkflowNodeVariableSet( null );
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }

        $this->fail();
    }

    public function testVariableUnsetConstructor()
    {
        try
        {
            $set = new ezcWorkflowNodeVariableUnset( null );
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }

        $this->fail();
    }

    public function testGetInNodes()
    {
        $this->setUpStartEnd();

        $inNodes = $this->endNode->getInNodes();

        $this->assertSame( $this->startNode, $inNodes[0] );
    }

    public function testGetOutNodes()
    {
        $this->setUpStartEnd();

        $outNodes = $this->startNode->getOutNodes();

        $this->assertSame( $this->endNode, $outNodes[0] );
    }

    public function testBranchGetCondition()
    {
        $this->setUpExclusiveChoiceSimpleMerge();

        $outNodes = $this->branchNode->getOutNodes();

        $this->assertEquals( 'condition is true', (string)$this->branchNode->getCondition( $outNodes[0] ) );
        $this->assertEquals( 'condition is false', (string)$this->branchNode->getCondition( $outNodes[1] ) );
    }

    public function testRemoveInNode()
    {
        $this->setUpStartEnd();

        $this->assertTrue( $this->endNode->removeInNode( $this->startNode ) );
        $this->assertFalse( $this->endNode->removeInNode( $this->startNode ) );
    }

    public function testRemoveOutNode()
    {
        $this->setUpStartEnd();

        $this->assertTrue( $this->startNode->removeOutNode( $this->endNode ) );
        $this->assertFalse( $this->startNode->removeOutNode( $this->endNode ) );
    }

    public function testToString()
    {
        $this->setUpEmptyWorkflow();

        $this->assertEquals( 'Start', (string)$this->startNode );
        $this->assertEquals( 'End', (string)$this->endNode );
    }

    public function testStartVerifyFails()
    {
        $this->setUpEmptyWorkflow();

        try
        {
            $this->startNode->verify();
        }
        catch ( ezcWorkflowInvalidDefinitionException $e )
        {
            return;
        }

        $this->fail();
    }

    public function testEndVerifyFails()
    {
        $this->setUpEmptyWorkflow();

        try
        {
            $this->endNode->verify();
        }
        catch ( ezcWorkflowInvalidDefinitionException $e )
        {
            return;
        }

        $this->fail();
    }

    public function testVerifyTooManyIncomingNodes()
    {
        try
        {
            $a = new ezcWorkflowNodeVariableSet(
              array( 'foo' => 'bar' )
            );

            $b = new ezcWorkflowNodeVariableSet(
              array( 'foo' => 'bar' )
            );

            $c = new ezcWorkflowNodeVariableSet(
              array( 'foo' => 'bar' )
            );

            $d = new ezcWorkflowNodeVariableSet(
              array( 'foo' => 'bar' )
            );

            $c->addInNode( $a );
            $c->addInNode( $b );
            $c->addOutNode( $d );

            $c->verify();
        }
        catch ( ezcWorkflowInvalidDefinitionException $e )
        {
            return;
        }

        $this->fail();
    }

    public function testVerifyTooManyOutgoingNodes()
    {
        try
        {
            $a = new ezcWorkflowNodeVariableSet(
              array( 'foo' => 'bar' )
            );

            $b = new ezcWorkflowNodeVariableSet(
              array( 'foo' => 'bar' )
            );

            $c = new ezcWorkflowNodeVariableSet(
              array( 'foo' => 'bar' )
            );

            $d = new ezcWorkflowNodeVariableSet(
              array( 'foo' => 'bar' )
            );

            $b->addOutNode( $c );
            $b->addOutNode( $d );
            $b->addInNode( $a );

            $b->verify();
        }
        catch ( ezcWorkflowInvalidDefinitionException $e )
        {
            return;
        }

        $this->fail();
    }
}
?>
