<?php
// Create new workflow of name "Test".
$workflow = new ezcWorkflow( 'Test' );

// Set up references to start / end nodes.
$start = $workflow->getStartNode();
$end   = $workflow->getEndNode();

// Create an Input node that expects a boolean variable of name "choice".
$input = new ezcWorkflowNodeInput(
  array(
    'choice' => new ezcWorkflowConditionIsBool
  )
);

// Add the previously created Input node
// as an outgoing node to the start node.
$start->addOutNode( $input );

// Create a new Exclusive Choice node and add it as an 
// outgoing node to the previously created Input node.
$branch = new ezcWorkflowNodeExclusiveChoice;
$branch->addInNode( $input );

$true  = new ezcWorkflowNodeAction( 'PrintTrue' );
$false = new ezcWorkflowNodeAction( 'PrintFalse' );

// Branch
// Condition: Variable "choice" has boolean value "true".
// Action:    PrintTrue service object.
$branch->addConditionalOutNode(
  new ezcWorkflowConditionVariable(
    'choice',
    new ezcWorkflowConditionIsTrue
  ),
  $true
);

// Branch
// Condition: Variable "choice" has boolean value "false".
// Action:    PrintFalse service object.
$branch->addConditionalOutNode(
  new ezcWorkflowConditionVariable(
    'choice',
    new ezcWorkflowConditionIsFalse
  ),
  $false
);

// Create SimpleMerge node and add the two possible threads of
// execution as incoming nodes.
$merge = new ezcWorkflowNodeSimpleMerge;
$merge->addInNode( $true );
$merge->addInNode( $false );
$merge->addOutNode( $end );
?>
