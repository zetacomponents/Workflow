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
        $this->workflow = $this->xmlStorage->loadByName( $workflowName );
        $this->workflow->reset();

        $this->xmlStorage->save( $this->workflow );

        $this->assertEquals(
          $this->readExpected( $workflowName ),
          $this->readActual( $workflowName )
        );
    }

    public function testLoadServiceObjectWithArguments2()
    {
        $this->workflow = $this->xmlStorage->loadByName( 'ServiceObjectWithArguments2' );
    }

    public function testLoadServiceObjectWithArguments3()
    {
        $this->workflow = $this->xmlStorage->loadByName( 'ServiceObjectWithArguments3' );
    }

    /**
     * @dataProvider workflowNameProvider
     */
    public function testSaveWorkflow($workflowName)
    {
        static $schema = null;

        if ( $schema === null )
        {
            $schema = dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR .
                      'design' . DIRECTORY_SEPARATOR . 'schema.rng';
        }

        $setupMethod = 'setUp' . $workflowName;

        $this->$setupMethod();
        $this->workflow->reset();

        $this->xmlStorage->save( $this->workflow );

        $expected = $this->readExpected( $workflowName );
        $actual   = $this->readActual( $workflowName );

        $this->assertEquals( $expected, $actual );

        $document = new DOMDocument;
        $document->loadXML( $actual );

        $this->assertTrue( $document->relaxngValidate( $schema ) );
    }

    public function testExceptionWhenLoadingNotExistingWorkflow()
    {
        try
        {
            $this->xmlStorage->loadByName( 'NotExisting' );
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
            $workflow = $this->xmlStorage->loadByName( 'StartEnd', 2 );
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
            $this->xmlStorage->loadByName( 'NotValid' );
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
            $this->xmlStorage->loadByName( 'NotWellFormed' );
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
