<?php
class MyServiceObject implements ezcWorkflowServiceObject
{
    private $message;

    public function __construct( $message )
    {
        $this->message = $message;
    }

    public function execute( ezcWorkflowExecution $execution )
    {
        echo $this->message;

        // Manipulate the workflow.
        // Does not affect the workflow, for illustration only.
        $execution->setVariable( 'choice', true );

        // Return true to signal that the service object has finished
        // executing.
        return true;
    }

    public function __toString()
    {
        return "MyServiceObject, message {$this->message}";
    }
}

$trueNode = new ezcWorkflowNodeAction( array( 'class' => 'MyServiceObject',
                                              'arguments' => array( 'message: TRUE' ) )
                                       );
$falseNode  = new ezcWorkflowNodeAction( array( 'class' => 'MyServiceObject',
                                                'arguments' => array( 'message: FALSE' ) )
                                         );
?>
