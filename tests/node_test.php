<?php
/**
 * @package Workflow
 * @subpackage Tests
 * @version //autogentag//
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
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

    /**
     * @expectedException ezcBaseValueException
     */
    public function testInputConstructor()
    {
        new ezcWorkflowNodeInput( null );
    }

    /**
     * @expectedException ezcBaseValueException
     */
    public function testInputConstructor2()
    {
        new ezcWorkflowNodeInput( array( 'foo' => new StdClass ) );
    }

    /**
     * @expectedException ezcBaseValueException
     */
    public function testInputConstructor3()
    {
        new ezcWorkflowNodeInput( array( new StdClass ) );
    }

    public function testInputConstructor4()
    {
        $input         = new ezcWorkflowNodeInput( array( 'variable' ) );
        $configuration = $input->getConfiguration();

        $this->assertArrayHasKey( 'variable', $configuration );
        $this->assertType( 'ezcWorkflowConditionIsAnything', $configuration['variable'] );
    }

    /**
     * @expectedException ezcBaseValueException
     */
    public function testVariableSetConstructor()
    {
        new ezcWorkflowNodeVariableSet( null );
    }

    /**
     * @expectedException ezcBaseValueException
     */
    public function testVariableUnsetConstructor()
    {
        new ezcWorkflowNodeVariableUnset( null );
    }

    public function testGetInNodes()
    {
        $this->setUpStartEnd();

        $inNodes = $this->workflow->endNode->getInNodes();

        $this->assertSame( $this->workflow->startNode, $inNodes[0] );
    }

    public function testGetOutNodes()
    {
        $this->setUpStartEnd();

        $outNodes = $this->workflow->startNode->getOutNodes();

        $this->assertSame( $this->workflow->endNode, $outNodes[0] );
    }

    public function testBranchGetCondition()
    {
        $this->setUpExclusiveChoiceSimpleMerge();

        $outNodes = $this->branchNode->getOutNodes();

        $this->assertEquals( 'condition is true', (string)$this->branchNode->getCondition( $outNodes[0] ) );
        $this->assertEquals( 'condition is false', (string)$this->branchNode->getCondition( $outNodes[1] ) );
    }

    public function testBranchGetCondition2()
    {
        $this->setUpExclusiveChoiceWithUnconditionalOutNodeSimpleMerge();

        $outNodes = $this->branchNode->getOutNodes();
        $this->assertFalse( $this->branchNode->getCondition( $outNodes[2] ) );
    }

    public function testBranchGetCondition3()
    {
        $this->setUpExclusiveChoiceWithUnconditionalOutNodeSimpleMerge();

        $this->assertFalse( $this->branchNode->getCondition( new ezcWorkflowNodeEnd ) );
    }

    public function testRemoveInNode()
    {
        $this->setUpStartEnd();

        $this->assertTrue( $this->workflow->endNode->removeInNode( $this->workflow->startNode ) );
        $this->assertFalse( $this->workflow->endNode->removeInNode( $this->workflow->startNode ) );
    }

    public function testRemoveOutNode()
    {
        $this->setUpStartEnd();

        $this->assertTrue( $this->workflow->startNode->removeOutNode( $this->workflow->endNode ) );
        $this->assertFalse( $this->workflow->startNode->removeOutNode( $this->workflow->endNode ) );
    }

    public function testToString()
    {
        $this->setUpEmptyWorkflow();

        $this->assertEquals( 'Start', (string)$this->workflow->startNode );
        $this->assertEquals( 'End', (string)$this->workflow->endNode );
    }

    /**
     * @expectedException ezcWorkflowInvalidWorkflowException
     */
    public function testStartVerifyFails()
    {
        $this->setUpEmptyWorkflow();
        $this->workflow->startNode->verify();
    }

    /**
     * @expectedException ezcWorkflowInvalidWorkflowException
     */
    public function testEndVerifyFails()
    {
        $this->setUpEmptyWorkflow();
        $this->workflow->endNode->verify();
    }

    /**
     * @expectedException ezcWorkflowInvalidWorkflowException
     */
    public function testVerifyTooManyIncomingNodes()
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

    /**
     * @expectedException ezcWorkflowInvalidWorkflowException
     */
    public function testVerifyTooManyOutgoingNodes()
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

    /**
     * @expectedException ezcWorkflowInvalidWorkflowException
     */
    public function testVerifyTooFewConditionalOutNodes()
    {
        $branch = new ezcWorkflowNodeExclusiveChoice;
        $branch->addInNode( new ezcWorkflowNodeStart )
               ->addOutNode( new ezcWorkflowNodeEnd )
               ->addOutNode( new ezcWorkflowNodeEnd );

        $branch->verify();
    }

    public function testActivatedFrom()
    {
        $node = new ezcWorkflowNodeStart;
        $this->assertEquals( array(), $node->getActivatedFrom() );
        $node->setActivatedFrom( array( TRUE ) );
        $this->assertEquals( array( TRUE ), $node->getActivatedFrom() );
    }

    public function testState()
    {
        $node = new ezcWorkflowNodeStart;
        $this->assertNull( $node->getState() );
        $node->setState( TRUE );
        $this->assertTrue( $node->getState() );
    }
}
?>
