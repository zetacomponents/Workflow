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

        try
        {
            $this->endNode->removeInNode( $this->startNode );
        }

        catch ( ezcWorkflowInvalidDefinitionException $e )
        {
            return;
        }

        $this->fail();
    }

    public function testRemoveOutNode()
    {
        $this->setUpStartEnd();

        try
        {
            $this->startNode->removeOutNode( $this->endNode );
        }

        catch ( ezcWorkflowInvalidDefinitionException $e )
        {
            return;
        }

        $this->fail();
    }

    public function testToString()
    {
        $this->setUpEmptyWorkflow();

        $this->assertEquals( 'Start', (string)$this->startNode );
        $this->assertEquals( 'End', (string)$this->endNode );
    }
}
?>
