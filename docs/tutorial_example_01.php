<?php
// Create new workflow of name "Test".
$workflow = new ezcWorkflow( 'Test' );

// Create an Input node that expects a boolean workflow variable of name "choice".
$input = new ezcWorkflowNodeInput(
  array( 'choice' => new ezcWorkflowConditionIsBool )
);

// Add the previously created Input node
// as an outgoing node to the start node.
$workflow->startNode->addOutNode( $input );

// Create a new Exclusive Choice node and add it as an
// outgoing node to the previously created Input node.
// This node will choose which output to run based on the
// choice workflow variable.
$branch = new ezcWorkflowNodeExclusiveChoice;
$branch->addInNode( $input );

// Either $true or $false will be run depending on
// the above choice.
// Note that neither $true nor $false are valid action nodes.
// see the next example
$trueNode  = new ezcWorkflowNodeAction( 'PrintTrue' );
$falseNode = new ezcWorkflowNodeAction( 'PrintFalse' );

// Branch
// Condition: Variable "choice" has boolean value "true".
// Action:    PrintTrue service object.
$branch->addConditionalOutNode(
    new ezcWorkflowConditionVariable( 'choice', new ezcWorkflowConditionIsTrue ),
    $trueNode );

// Branch
// Condition: Variable "choice" has boolean value "false".
// Action:    PrintFalse service object.
$branch->addConditionalOutNode(
  new ezcWorkflowConditionVariable( 'choice', new ezcWorkflowConditionIsFalse ),
  $falseNode
);

// Create SimpleMerge node and add the two possible threads of
// execution as incoming nodes of the end node.
$merge = new ezcWorkflowNodeSimpleMerge;
$merge->addInNode( $trueNode );
$merge->addInNode( $falseNode );
$merge->addOutNode( $workflow->endNode );
?>
