<?php
require_once '../../../../trunk/Base/src/base.php';

function __autoload( $className )
{
    ezcBase::autoload( $className );
}

class PrintTrue implements ezcWorkflowServiceObject
{
    public function execute( ezcWorkflowExecution $execution )
    {
        print "TRUE\n";
    }

    public function __toString()
    {
        return 'PrintTrue';
    }
}

class PrintFalse implements ezcWorkflowServiceObject
{
    public function execute( ezcWorkflowExecution $execution )
    {
        print "FALSE\n";
    }

    public function __toString()
    {
        return 'PrintFalse';
    }
}
?>
