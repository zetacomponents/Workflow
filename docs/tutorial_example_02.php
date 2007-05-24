<?php
// Set up workflow definition storage (XML).
$definition = new ezcWorkflowDefinitionXml( '/path/to/directory' );

// Save workflow definition to database.
$definition->save( $workflow );
?>
