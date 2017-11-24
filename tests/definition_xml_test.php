<?php
/**
 *
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 * 
 *   http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 *
 * @package Workflow
 * @subpackage Tests
 * @version //autogentag//
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
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

    protected function tearDown()
    {
        $this->removeTempDir();
    }

    /**
     * @dataProvider workflowNameProvider
     */
    public function testLoadWorkflow( $workflowName, $numNodes )
    {
        $this->workflow = $this->xmlStorage->loadByName( $workflowName );
        $this->workflow->reset();

        $this->xmlStorage->save( $this->workflow );

        $this->assertEquals(
          $this->readExpected( $workflowName ),
          $this->readActual( $workflowName )
        );

        $this->assertEquals( $numNodes, count( $this->workflow ) );
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
    public function testSaveWorkflow( $workflowName )
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

    /**
     * @ticket 14437
     */
    public function testEditWorkflow()
    {
        $tmpDirectory = $this->createTempDir( 'workflow' . DIRECTORY_SEPARATOR );
        $this->xmlStorage = new ezcWorkflowDefinitionStorageXml( $tmpDirectory );

        $this->workflow = new ezcWorkflow( 'Edit' );
        $this->assertEquals( 1, count( $this->workflow ) );

        $this->workflow->startNode->addOutNode( $this->workflow->endNode );
        $this->assertEquals( 2, count( $this->workflow ) );

        $this->xmlStorage->save( $this->workflow );

        $this->assertFileEquals(
          dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'Edit_1.xml', $tmpDirectory . 'Edit_1.xml'
        );

        $this->workflow = $this->xmlStorage->loadByName( 'Edit' );
        $this->assertEquals( 2, count( $this->workflow ) );

        $this->workflow->endNode->removeInNode( $this->workflow->startNode );
        $this->assertEquals( 1, count( $this->workflow ) );

        $inputNode = new ezcWorkflowNodeInput( array( 'variable' => new ezcWorkflowConditionIsString ) );
        $this->workflow->startNode->addOutNode( $inputNode );
        $this->workflow->endNode->addInNode( $inputNode );
        $this->assertEquals( 3, count( $this->workflow ) );

        $this->xmlStorage->save( $this->workflow );

        $this->assertFileEquals(
          dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'Edit_2.xml', $tmpDirectory . 'Edit_2.xml'
        );
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

    /**
     * @ticket 14754
     */
    public function testIssue14754()
    {
        $workflow = new ezcWorkflow( 'Issue14754' );
        $set      = new ezcWorkflowNodeVariableSet( array( 'x' => 1 ) );
        $workflow->startNode->addOutNode( $set );
        $workflow->endNode->addInNode( $set );

        $expected = $this->xmlStorage->saveToDocument( $workflow, 0 );
        $actual   = $this->xmlStorage->saveToDocument(
          $this->xmlStorage->loadFromDocument( $expected ), 0
        );

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @ticket 16867
     */
    public function testIssue16867()
    {
        $workflow = new ezcWorkflow( 'Issue16867' );
        $set      = new ezcWorkflowNodeVariableSet( array( 'path' => true ) );
        $xor      = new ezcWorkflowNodeExclusiveChoice;
        $dummy1   = new ezcWorkflowNodeVariableSet( array( 'dummy' => 1 ) );
        $dummy2   = new ezcWorkflowNodeVariableSet( array( 'dummy' => 2 ) );
        $merge    = new ezcWorkflowNodeSimpleMerge;
        $workflow->startNode->addOutNode( $set );
        $set->addOutNode( $xor );
        $xor->addConditionalOutNode(
          new ezcWorkflowConditionVariable(
            'path',
            new ezcWorkflowConditionIsTrue
          ),
          $dummy1,
          $dummy2
        );
        $dummy1->addOutNode( $merge );
        $dummy2->addOutNode( $merge );
        $merge->addOutNode( $workflow->endNode );
        $workflow->definitionStorage = $this->xmlStorage;

        $this->assertEquals(
          $workflow,
          $this->xmlStorage->loadFromDocument(
            $this->xmlStorage->saveToDocument( $workflow, 1 )
          )
        );
    }

    protected function readActual( $name )
    {
        $actual = str_replace(
          'version="2"',
          'version="1"',
          file_get_contents(
            dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $name . '_2.xml'
          )
        );

        @unlink( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $name . '_2.xml' );

        return $actual;
    }

    protected function readExpected( $name )
    {
        return file_get_contents(
          dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $name . '_1.xml'
        );
    }
}
?>
