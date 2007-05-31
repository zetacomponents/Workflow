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

        $this->workflow->definitionStorage = $this->definition;
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
            return;
        }

        $this->fail();
    }

    public function testGetNodes()
    {
        $this->setUpStartEnd();
        $nodes = $this->workflow->nodes;

        $this->assertSame( $this->startNode, $nodes[1] );
        $this->assertSame( $this->endNode, $nodes[2] );
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
        try
        {
            $workflow = new ezcWorkflow( 'Test' );
            $workflow->verify();
        }
        catch ( ezcWorkflowDefinitionStorageException $e )
        {
            return;
        }

        $this->fail();
    }

    public function testVerify3()
    {
        try
        {
            $workflow = new ezcWorkflow( 'Test' );
            $workflow->startNode->addOutNode( new ezcWorkflowNodeStart );
            $workflow->verify();
        }
        catch ( ezcWorkflowDefinitionStorageException $e )
        {
            return;
        }

        $this->fail();
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
            $this->workflow->addVariableHandler(
              'foo', 'StdClass'
            );
        }
        catch ( ezcWorkflowInvalidWorkflowException $e )
        {
            return;
        }

        $this->fail();
    }

    public function testVariableHandler3()
    {
        $this->setUpStartEnd();

        try
        {
            $this->workflow->addVariableHandler(
              'foo', 'NotExisting'
            );
        }
        catch ( ezcWorkflowInvalidWorkflowException $e )
        {
            return;
        }

        $this->fail();
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
            return;
        }

        $this->fail();
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
            return;
        }

        $this->fail();
    }

    public function testProperties4()
    {
        $this->setUpStartEnd();

        try
        {
            $foo = $this->workflow->definitionStorage = null;
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }

        $this->fail();
    }

    public function testProperties5()
    {
        $this->setUpStartEnd();

        try
        {
            $foo = $this->workflow->id = null;
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }

        $this->fail();
    }

    public function testProperties6()
    {
        $this->setUpStartEnd();

        try
        {
            $foo = $this->workflow->name = null;
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }

        $this->fail();
    }

    public function testProperties7()
    {
        $this->setUpStartEnd();

        try
        {
            $foo = $this->workflow->nodes = null;
        }
        catch ( ezcBasePropertyPermissionException $e )
        {
            return;
        }

        $this->fail();
    }

    public function testProperties8()
    {
        $this->setUpStartEnd();

        try
        {
            $foo = $this->workflow->version = null;
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }

        $this->fail();
    }
}
?>
