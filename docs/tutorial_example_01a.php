class MyServiceObject implements ezcWorkflowServiceObject
{
    private $message;

    public function construct( $message )
    {
        $this->message = $message;
    }

    public function execute( ezcWorkflowExecution $execution )
    {
        echo $this->message;

        // manipulate the workflow..
        // doesn't affect the workflow, for illustration only
        $execution->setVariable( 'choice', true );
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
