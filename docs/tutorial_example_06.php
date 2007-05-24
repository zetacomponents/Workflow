<?php
// Generate GraphViz/dot markup for workflow "Test".
$visitor = new ezcWorkflowVisitorVisualization;
$workflow->accept( $visitor );
print $visitor;
?>
