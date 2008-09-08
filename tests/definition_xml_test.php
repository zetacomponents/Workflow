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
class ezcWorkflowDefinitionStorageXmlTest extends ezcWorkflowTestCase
{
    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( 'ezcWorkflowDefinitionStorageXmlTest' );
    }

    /**
     * @dataProvider workflowNameProvider
     */
    public function testLoadWorkflow($workflowName)
    {
        $this->workflow = $this->definition->loadByName( $workflowName );
        $this->workflow->reset();

        $this->definition->save( $this->workflow );

        $this->assertEquals(
          $this->readExpected( $workflowName ),
          $this->readActual( $workflowName )
        );
    }

    public function testLoadServiceObjectWithArguments2()
    {
        $this->workflow = $this->definition->loadByName( 'ServiceObjectWithArguments2' );
    }

    public function testLoadServiceObjectWithArguments3()
    {
        $this->workflow = $this->definition->loadByName( 'ServiceObjectWithArguments3' );
    }

    /**
     * @dataProvider workflowNameProvider
     */
    public function testSaveWorkflow($workflowName)
    {
        $setupMethod = 'setUp' . $workflowName;

        $this->$setupMethod();
        $this->workflow->reset();

        $this->definition->save( $this->workflow );

        $this->assertEquals(
          $this->readExpected( $workflowName ),
          $this->readActual( $workflowName )
        );
    }

    public function testExceptionWhenLoadingNotExistingWorkflow()
    {
        try
        {
            $this->definition->loadByName( 'NotExisting' );
        }
        catch ( ezcWorkflowDefinitionStorageException $e )
        {
            return;
        }

        $this->fail( 'Expected an ezcWorkflowDefinitionStorageException to be thrown.' );
    }

    public function testExceptionWhenLoadingNotExistingWorkflowVersion()
    {
        try
        {
            $workflow = $this->definition->loadByName( 'StartEnd', 2 );
        }
        catch ( ezcWorkflowDefinitionStorageException $e )
        {
            return;
        }

        $this->fail( 'Expected an ezcWorkflowDefinitionStorageException to be thrown.' );
    }

    public function testExceptionWhenLoadingNotValidWorkflow()
    {
        try
        {
            $this->definition->loadByName( 'NotValid' );
        }
        catch ( ezcWorkflowDefinitionStorageException $e )
        {
            $this->assertEquals( 'Could not load workflow definition.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcWorkflowDefinitionStorageException to be thrown.' );
    }

    public function testExceptionWhenLoadingNotWellFormedWorkflow()
    {
        try
        {
            $this->definition->loadByName( 'NotWellFormed' );
        }
        catch ( ezcWorkflowDefinitionStorageException $e )
        {
            return;
        }

        $this->fail( 'Expected an ezcWorkflowDefinitionStorageException to be thrown.' );
    }

    protected function readActual( $name )
    {
        $actual = str_replace(
          'version="2"',
          'version="1"',
          file_get_contents(
            dirname( __FILE__ ) . '/data/' . $name . '_2.xml'
          )
        );

        @unlink( dirname( __FILE__ ) . '/data/' . $name . '_2.xml' );

        return $actual;
    }

    protected function readExpected( $name )
    {
        return file_get_contents(
          dirname( __FILE__ ) . '/data/' . $name . '_1.xml'
        );
    }
}
?>
