<?php
/**
 * @package Workflow
 * @subpackage Tests
 * @version //autogentag//
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

require_once 'case.php';

/**
 * @package Workflow
 * @subpackage Tests
 */
class ezcWorkflowTest extends ezcWorkflowTestCase
{
    protected $workflow;
    protected $startNode;
    protected $endNode;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( 'ezcWorkflowTest' );
    }

    public function testGetName()
    {
        $this->setUpStartEnd();
        $this->assertEquals( 'StartEnd', $this->workflow->name );
    }

    public function testGetSetId()
    {
        $this->setUpStartEnd();
        $this->assertFalse( $this->workflow->id );

        $this->workflow->id = 1;

        $this->assertEquals( 1, $this->workflow->id );
    }

    public function testGetSetDefinition()
    {
        $this->setUpStartEnd();
        $this->assertNull( $this->workflow->definitionStorage );

        $this->workflow->definitionStorage = $this->xmlStorage;
        $this->assertNotNull( $this->workflow->definitionStorage );
    }

    public function testGetSetName()
    {
        $workflow = new ezcWorkflow( 'Test' );
        $this->assertEquals( 'Test', $workflow->name );

        $workflow->name = 'Test2';
        $this->assertEquals( 'Test2', $workflow->name );

        try
        {
            $workflow->name = array();
        }
        catch ( ezcBaseValueException $e )
        {
            $this->assertEquals( 'The value \'a:0:{}\' that you were trying to assign to setting \'name\' is invalid. Allowed values are: string.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcBaseValueException to be thrown.' );
    }

    public function testGetNodes()
    {
        $this->setUpStartEnd();
        $nodes = $this->workflow->nodes;

        $this->assertSame( $this->workflow->startNode, $nodes[1] );
        $this->assertSame( $this->workflow->endNode, $nodes[2] );
    }

    public function testHasSubWorkflows()
    {
        $this->setUpStartEnd();
        $this->assertFalse( $this->workflow->hasSubWorkflows() );

        $this->setUpWorkflowWithSubWorkflow( 'StartEnd' );
        $this->assertTrue( $this->workflow->hasSubWorkflows() );
    }

    public function testIsInteractive()
    {
        $this->setUpStartEnd();
        $this->assertFalse( $this->workflow->isInteractive() );
    }

    public function testVerify()
    {
        $this->setUpStartEnd();
        $this->workflow->verify();
    }

    public function testVerify2()
    {
        $workflow = new ezcWorkflow( 'Test' );

        try
        {
            $workflow->verify();
        }
        catch ( ezcWorkflowDefinitionStorageException $e )
        {
            $this->assertEquals( 'Node of type "Start" has less outgoing nodes than required.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcWorkflowDefinitionStorageException to be thrown.' );
    }

    public function testVerify3()
    {
        $workflow = new ezcWorkflow( 'Test' );
        $workflow->startNode->addOutNode( new ezcWorkflowNodeStart );

        try
        {
            $workflow->verify();
        }
        catch ( ezcWorkflowDefinitionStorageException $e )
        {
            $this->assertEquals( 'A workflow may have only one start node.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcWorkflowDefinitionStorageException to be thrown.' );
    }

    public function testVerify4()
    {
        $workflow = new ezcWorkflow( 'Test' );
        $workflow->finallyNode->addOutNode( new ezcWorkflowNodeFinally );

        try
        {
            $workflow->verify();
        }
        catch ( ezcWorkflowDefinitionStorageException $e )
        {
            $this->assertEquals( 'A workflow may have only one finally node.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcWorkflowDefinitionStorageException to be thrown.' );
    }

    public function testVariableHandler()
    {
        $this->setUpStartEnd();

        $this->assertFalse( $this->workflow->removeVariableHandler( 'foo' ) );

        $this->workflow->setVariableHandlers(
          array( 'foo' => 'ezcWorkflowTestVariableHandler' )
        );

        $this->assertTrue( $this->workflow->removeVariableHandler( 'foo' ) );
    }

    public function testVariableHandler2()
    {
        $this->setUpStartEnd();

        try
        {
            $this->workflow->addVariableHandler( 'foo', 'StdClass' );
        }
        catch ( ezcWorkflowInvalidWorkflowException $e )
        {
            $this->assertEquals( 'Class "StdClass" does not implement the ezcWorkflowVariableHandler interface.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcWorkflowInvalidWorkflowException to be thrown.' );
    }

    public function testVariableHandler3()
    {
        $this->setUpStartEnd();

        try
        {
            $this->workflow->addVariableHandler( 'foo', 'NotExisting' );
        }
        catch ( ezcWorkflowInvalidWorkflowException $e )
        {
            $this->assertEquals( 'Class "NotExisting" not found.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcWorkflowInvalidWorkflowException to be thrown.' );
    }

    public function testProperties()
    {
        $this->setUpStartEnd();

        $this->assertTrue( isset( $this->workflow->definitionStorage ) );
        $this->assertTrue( isset( $this->workflow->id ) );
        $this->assertTrue( isset( $this->workflow->name ) );
        $this->assertTrue( isset( $this->workflow->nodes ) );
        $this->assertTrue( isset( $this->workflow->version ) );
        $this->assertFalse( isset( $this->workflow->foo ) );
    }

    public function testProperties2()
    {
        $this->setUpStartEnd();

        try
        {
            $foo = $this->workflow->foo;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            $this->assertEquals( 'No such property name \'foo\'.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcBasePropertyNotFoundException to be thrown.' );
    }

    public function testProperties3()
    {
        $this->setUpStartEnd();

        try
        {
            $this->workflow->foo = null;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            $this->assertEquals( 'No such property name \'foo\'.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcBasePropertyNotFoundException to be thrown.' );
    }

    public function testProperties4()
    {
        $this->setUpStartEnd();

        try
        {
            $this->workflow->definitionStorage = null;
        }
        catch ( ezcBaseValueException $e )
        {
            $this->assertEquals( 'The value \'\' that you were trying to assign to setting \'definitionStorage\' is invalid. Allowed values are: ezcWorkflowDefinitionStorage.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcBaseValueException to be thrown.' );
    }

    public function testProperties5()
    {
        $this->setUpStartEnd();

        try
        {
            $this->workflow->id = null;
        }
        catch ( ezcBaseValueException $e )
        {
            $this->assertEquals( 'The value \'\' that you were trying to assign to setting \'id\' is invalid. Allowed values are: integer.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcBaseValueException to be thrown.' );
    }

    public function testProperties6()
    {
        $this->setUpStartEnd();

        try
        {
            $this->workflow->name = null;
        }
        catch ( ezcBaseValueException $e )
        {
            $this->assertEquals( 'The value \'\' that you were trying to assign to setting \'name\' is invalid. Allowed values are: string.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcBaseValueException to be thrown.' );
    }

    public function testProperties7()
    {
        $this->setUpStartEnd();

        try
        {
            $this->workflow->nodes = null;
        }
        catch ( ezcBasePropertyPermissionException $e )
        {
            $this->assertEquals( 'The property \'nodes\' is read-only.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcBasePropertyPermissionException to be thrown.' );
    }

    public function testProperties8()
    {
        $this->setUpStartEnd();

        try
        {
            $this->workflow->version = null;
        }
        catch ( ezcBaseValueException $e )
        {
            $this->assertEquals( 'The value \'\' that you were trying to assign to setting \'version\' is invalid. Allowed values are: integer.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcBaseValueException to be thrown.' );
    }

    public function testForIssue14451()
    {
        $this->workflow = new ezcWorkflow( 'Test' );

        $this->assertEquals( 1, count( $this->workflow ) );
        $this->assertEquals( 1, count( $this->workflow->nodes ) );

        $this->workflow->startNode->addOutNode( $this->workflow->endNode );

        $this->assertEquals( 2, count( $this->workflow ) );
        $this->assertEquals( 2, count( $this->workflow->nodes ) );

        $this->workflow->startNode->removeOutNode( $this->workflow->endNode );

        $this->assertEquals( 1, count( $this->workflow ) );
        $this->assertEquals( 1, count( $this->workflow->nodes ) );

        $input = new ezcWorkflowNodeInput( array( 'value' => new ezcWorkflowConditionIsInteger ) );
        $this->workflow->startNode->addOutNode( $input );

        $this->assertEquals( 2, count( $this->workflow ) );
        $this->assertEquals( 2, count( $this->workflow->nodes ) );

        $choice = new ezcWorkflowNodeExclusiveChoice;
        $input->addOutNode( $choice );

        $this->assertEquals( 3, count( $this->workflow ) );
        $this->assertEquals( 3, count( $this->workflow->nodes ) );

        $branch1 = new ezcWorkflowNodeInput( array( 'value' => new ezcWorkflowConditionIsAnything ) );
        $branch2 = new ezcWorkflowNodeInput( array( 'value' => new ezcWorkflowConditionIsAnything ) );

        $choice->addConditionalOutNode( new ezcWorkflowConditionIsAnything , $branch1 );

        $this->assertEquals( 4, count( $this->workflow ) );
        $this->assertEquals( 4, count( $this->workflow->nodes ) );

        $choice->addConditionalOutNode( new ezcWorkflowConditionIsAnything , $branch2 );

        $this->assertEquals( 5, count( $this->workflow ) );
        $this->assertEquals( 5, count( $this->workflow->nodes ) );

        $merge = new ezcWorkflowNodeSimpleMerge;
        $merge->addInNode( $branch1 );
        $merge->addInNode( $branch2 );
        $merge->addOutNode( $this->workflow->endNode );

        $this->assertEquals( 7, count( $this->workflow ) );
        $this->assertEquals( 7, count( $this->workflow->nodes ) );
    }
}
?>
