<?php
// Set up workflow definition storage (XML).
$definition = new ezcWorkflowDefinitionXml( '/path/to/directory' );

// Load latest version of workflow named "Test".
$workflow = $definition->loadByName( 'Test' );
?>
