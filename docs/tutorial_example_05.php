<?php
// Set up database connection.
$db = ezcDbFactory::create( 'mysql://test@localhost/test' );

// Set up workflow definition storage (database).
$definition = new ezcWorkflowDatabaseDefinitionStorage( $db );

// Load latest version of workflow named "Test".
$workflow = $definition->loadByName( 'Test' );
?>
