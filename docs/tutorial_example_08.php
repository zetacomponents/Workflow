<?php
// Set up database connection.
$db = ezcDbFactory::create( 'mysql://test@localhost/test' );

// Set up database-based workflow executer.
$execution = new ezcWorkflowDatabaseExecution( $db );

// Resume workflow execution.
$execution->resume(
  $id,                      // Execution ID.
  array( 'choice' => true ) // Input data.
);
?>
