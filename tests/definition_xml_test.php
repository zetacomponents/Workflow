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
        $this->definition->save( $this->workflow );

        $this->assertEquals(
          $this->readExpected( $workflowName ),
          $this->readActual( $workflowName )
        );
    }

    /**
     * @dataProvider workflowNameProvider
     */
    public function testSaveWorkflow($workflowName)
    {
        $setupMethod = 'setUp' . $workflowName;

        $this->$setupMethod();
        $this->definition->save( $this->workflow );

        $this->assertEquals(
          $this->readExpected( $workflowName ),
          $this->readActual( $workflowName )
        );
    }

    /**
     * @expectedException ezcWorkflowDefinitionStorageException
     */
    public function testExceptionWhenLoadingNotExistingWorkflow()
    {
        $this->definition->loadByName( 'NotExisting' );
    }

    /**
     * @expectedException ezcWorkflowDefinitionStorageException
     */
    public function testExceptionWhenLoadingNotExistingWorkflowVersion()
    {
        $workflow = $this->definition->loadByName( 'StartEnd', 2 );
    }

    /**
     * @expectedException ezcWorkflowDefinitionStorageException
     */
    public function testExceptionWhenLoadingNotValidWorkflow()
    {
        $this->definition->loadByName( 'NotValid' );
    }

    /**
     * @expectedException ezcWorkflowDefinitionStorageException
     */
    public function testExceptionWhenLoadingNotWellFormedWorkflow()
    {
        $this->definition->loadByName( 'NotWellFormed' );
    }

    public static function workflowNameProvider()
    {
        return array(
          array( 'AddVariables' ),
          array( 'DecrementingLoop' ),
          array( 'ExclusiveChoiceSimpleMerge' ),
          array( 'ExclusiveChoiceWithElseSimpleMerge' ),
          array( 'ExclusiveChoiceWithUnconditionalOutNodeSimpleMerge' ),
          array( 'IncrementingLoop' ),
          array( 'MultiChoiceDiscriminator' ),
          array( 'MultiChoiceSynchronizingMerge' ),
          array( 'NestedExclusiveChoiceSimpleMerge' ),
          array( 'NestedLoops' ),
          array( 'ParallelSplitSynchronization' ),
          array( 'ParallelSplitSynchronization2' ),
          array( 'ParallelSplitActionActionCancelCaseSynchronization' ),
          array( 'ParallelSplitCancelCaseActionActionSynchronization' ),
          array( 'ServiceObjectWithArguments' ),
          array( 'SetAddSubMulDiv' ),
          array( 'StartEnd' ),
          array( 'StartInputEnd' ),
          array( 'StartInputEnd2' ),
          array( 'StartEndVariableHandler' ),
          array( 'StartSetEnd' ),
          array( 'StartSetUnsetEnd' ),
          array( 'VariableEqualsVariable' ),
          array( 'WorkflowWithFinalActivitiesAfterCancellation' ),
          array( 'WorkflowWithSubWorkflowStartEnd' ),
          array( 'WorkflowWithSubWorkflowAndVariablePassing' ),
          array( 'WorkflowWithSubWorkflowParallelSplitActionActionCancelCaseSynchronization' )
        );
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
