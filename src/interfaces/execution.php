<?php
/**
 * File containing the ezcWorkflowExecution class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Abstract base class for workflow execution engines.
 *
 * @package Workflow
 * @version //autogen//
 */
abstract class ezcWorkflowExecution
{
    /**
     * Container to hold the properties
     *
     * @var array(string=>mixed)
     */
    protected $properties = array(
      'definitionHandler' => null,
      'workflow' => null
    );

    /**
     * Execution ID.
     *
     * @var integer
     */
    protected $id;

    /**
     * Nodes of the workflow being executed that are activated.
     *
     * @var ezcWorkflowNode[]
     */
    protected $activatedNodes = array();

    /**
     * Number of activated nodes.
     *
     * @var integer
     */
    protected $numActivatedNodes = 0;

    /**
     * Number of activated end nodes.
     *
     * @var integer
     */
    protected $numActivatedEndNodes = 0;

    /**
     * Nodes of the workflow that started a new thread of execution.
     *
     * @var array
     */
    protected $threads = array();

    /**
     * Sequence for thread ids.
     *
     * @var integer
     */
    protected $nextThreadId = 0;

    /**
     * @var boolean
     */
    protected $ended;

    /**
     * @var boolean
     */
    protected $resumed;

    /**
     * @var boolean
     */
    protected $suspended;

    /**
     * Listeners attached to this execution.
     *
     * @var array
     */
    protected $listeners = array();

    /**
     * @var array
     */
    protected $variables = array();

    /**
     * @var array
     */
    protected $waitingFor = array();

    /**
     * Property read access.
     *
     * @throws ezcBasePropertyNotFoundException 
     *         If the the desired property is not found.
     * 
     * @param string $propertyName Name of the property.
     * @return mixed Value of the property or null.
     * @ignore
     */
    public function __get( $propertyName )
    {
        switch ( $propertyName ) 
        {
            case 'definitionHandler':
            case 'workflow':
                return $this->properties[$propertyName];
        }

        throw new ezcBasePropertyNotFoundException( $propertyName );
    }

    /**
     * Property write access.
     * 
     * @param string $propertyName Name of the property.
     * @param mixed $val  The value for the property.
     *
     * @throws ezcBaseValueException 
     *         If a the value for the property definitionHandler is not an
     *         instance of ezcWorkflowDefinitionStorage.
     * @throws ezcBaseValueException 
     *         If a the value for the property workflow is not an instance of
     *         ezcWorkflow.
     * @ignore
     */
    public function __set( $propertyName, $val )
    {
        switch ( $propertyName ) 
        {
            case 'definitionHandler':
                if ( !( $val instanceof ezcWorkflowDefinitionStorage ) )
                {
                    throw new ezcBaseValueException( $propertyName, $val, 'ezcWorkflowDefinitionStorage' );
                }

                $this->properties['definitionHandler'] = $val;

                return;

            case 'workflow':
                if ( !( $val instanceof ezcWorkflow ) )
                {
                    throw new ezcBaseValueException( $propertyName, $val, 'ezcWorkflow' );
                }

                $this->properties['workflow'] = $val;

                return;
        }

        throw new ezcBasePropertyNotFoundException( $propertyName );
    }
 
    /**
     * Property isset access.
     * 
     * @param string $propertyName Name of the property.
     * @return bool True is the property is set, otherwise false.
     * @ignore
     */
    public function __isset( $propertyName )
    {
        switch ( $propertyName )
        {
            case 'definitionHandler':
            case 'workflow':
                return true;
        }

        return false;
    }

    /**
     * Starts the execution of the workflow.
     *
     * @todo what is parentId? How does it know what. Who calls this and when?
     * workflow to execute?
     * @param integer $parentId
     */
    public function start( $parentId = 0 )
    {
        $this->ended     = false;
        $this->resumed   = false;
        $this->suspended = false;

        $this->doStart( $parentId );
        $this->loadFromVariableHandlers();

        $this->notifyListeners(
          sprintf(
            'Started execution #%d of workflow "%s" (version %d).',

            $this->id,
            $this->workflow->name,
            $this->workflow->version
          )
        );

        // Start workflow execution by activating the start node.
        $this->workflow->startNode->activate( $this );

        // Continue workflow execution until there are no more
        // activated nodes.
        $this->execute();

        // Return execution ID if the workflow has been suspended.
        if ( $this->isSuspended() )
        {
            return $this->id;
        }
    }

    /**
     * Suspend workflow execution.
     * @todo what exactly does this method do? Lots of magic going on here.. who calls this and when?
     */
    public function suspend()
    {
        $this->ended     = false;
        $this->resumed   = false;
        $this->suspended = true;

        $keys     = array_keys( $this->variables );
        $count    = count( $keys );
        $handlers = $this->workflow->getVariableHandlers();

        for ( $i = 0; $i < $count; $i++ )
        {
            if ( isset( $handlers[$keys[$i]] ) )
            {
                unset( $this->variables[$keys[$i]] );
            }
        }

        $this->doSuspend();
        $this->saveToVariableHandlers();

        $this->notifyListeners(
          sprintf(
            'Suspended execution #%d of workflow "%s" (version %d).',

            $this->id,
            $this->workflow->name,
            $this->workflow->version
          )
        );
    }

    /**
     * Resume workflow execution.
     *
     * The format of $inputData is array( 'variableName' => value )
     * @todo explain executionId..., who calls this and when?
     * @param integer $executionId  ID of the execution to resume.
     * @param array   $inputData    The new input data.
     * @throws ezcWorkflowInvalidInputException if the input given does not match the expected data.
     * @throws ezcWorkflowExecutionException if executionId is not given and there is no prior ID for this execution.
     */
    public function resume( $executionId = false, Array $inputData = array() )
    {
        $this->ended     = false;
        $this->resumed   = true;
        $this->suspended = false;

        if ( $executionId === false )
        {
            if ( $this->id !== false )
            {
                $executionId = $this->id;
            }
            else
            {
                throw new ezcWorkflowExecutionException(
                  'No execution id given.'
                );
            }
        }

        $this->doResume( $executionId );
        $this->loadFromVariableHandlers();

        foreach ( $inputData as $variableName => $value )
        {
            if ( isset( $this->waitingFor[$variableName] ) )
            {
                if ( $this->waitingFor[$variableName]['condition']->evaluate( $value ) )
                {
                    $this->setVariable( $variableName, $value );
                    unset( $this->waitingFor[$variableName] );
                }
                else
                {
                    throw new ezcWorkflowInvalidInputException(
                      (string)$this->waitingFor[$variableName]['condition']
                    );
                }
            }
        }

        $this->notifyListeners(
          sprintf(
            'Resumed execution #%d of workflow "%s" (version %d).',

            $this->id,
            $this->workflow->name,
            $this->workflow->version
          )
        );

        $this->execute();

        // Return execution ID if the workflow has been suspended.
        if ( $this->isSuspended() )
        {
            return $this->id;
        }
    }

    /**
     * End workflow execution.
     * @todo who calls this and when?
     */
    public function end( ezcWorkflowNodeEnd $endNode )
    {
        $this->ended     = true;
        $this->resumed   = false;
        $this->suspended = false;

        $this->doEnd();
        $this->saveToVariableHandlers();

        $this->notifyListeners(
          sprintf(
            'Executed node #%d(%s) for instance #%d ' .
            'of workflow "%s" (version %d).',

            $endNode->getId(),
            get_class( $endNode ),
            $this->id,
            $this->workflow->name,
            $this->workflow->version
          ),
          ezcWorkflowExecutionListener::DEBUG
        );

        $this->endThread( $endNode->getThreadId() );

        $this->notifyListeners(
          sprintf(
            'Ended execution #%d of workflow "%s" (version %d).',

            $this->id,
            $this->workflow->name,
            $this->workflow->version
          )
        );
    }

    /**
     * The workflow engine's main execution loop. It is started by start() and
     * resume().
     */
    protected function execute()
    {
        // Try to execute nodes until while there are executable nodes on the
        // stack of activated nodes.
        do
        {
            // Flag that indicates whether a node has been executed during the
            // current iteration of the loop.
            $executed = false;

            // Iterate the stack of activated nodes.
            foreach ( $this->activatedNodes as $key => $node )
            {
                // Only try to execute a node if the execution of the
                // workflow instance has not ended yet.
                if ( !$this->hasEnded() )
                {
                    // The current node is an end node but there are still
                    // activated nodes on the stack.
                    if ( $node instanceof ezcWorkflowNodeEnd &&
                         $this->numActivatedNodes != $this->numActivatedEndNodes )
                    {
                        continue;
                    }

                    // Execute the current node and check whether it finished
                    // executing.
                    if ( $node->execute( $this ) )
                    {
                        // Remove current node from the stack of activated
                        // nodes.
                        unset( $this->activatedNodes[$key] );
                        $this->numActivatedNodes--;

                        // Notify workflow listeners about the node that has
                        // been executed.
                        if ( !$this->hasEnded() )
                        {
                            $this->notifyListeners(
                              sprintf(
                                'Executed node #%d(%s) for instance #%d ' .
                                'of workflow "%s" (version %d).',

                                $node->getId(),
                                get_class( $node ),
                                $this->id,
                                $this->workflow->name,
                                $this->workflow->version
                              ),
                              ezcWorkflowExecutionListener::DEBUG
                            );
                        }

                        // Toggle flag (see above).
                        $executed = true;
                    }
                }
            }
        }
        while ( !empty( $this->activatedNodes ) && $executed );

        // The stack of activated nodes is not empty but at the moment none of
        // its nodes can be executed.
        if ( !$this->hasEnded() )
        {
            $this->suspend();
        }
    }

    /**
     * Activates a node and returns true if it was activated, false if not.
     *
     * The node will only be activaated if the node is executable.
     * See ezcWorkflowNode::isExecutable().
     *
     * @todo correct see, what exactly does it mean to be activated?
     * @param ezcWorkflowNode $node
     * @return boolean
     */
    public function activate( ezcWorkflowNode $node )
    {
        // Check whether the node is ready to be activated
        // and not yet activated.
        if ( !$node->isExecutable() ||
             ezcWorkflowUtil::findObject( $this->activatedNodes, $node ) !== FALSE )
        {
            return false;
        }

        // Add node to list of activated nodes.
        $this->activatedNodes[] = $node;
        $this->numActivatedNodes++;

        if ( $node instanceof ezcWorkflowNodeEnd )
        {
            $this->numActivatedEndNodes++;
        }

        $this->notifyListeners(
          sprintf(
            'Activated node #%d(%s) for instance #%d ' .
            'of workflow "%s" (version %d).',

            $node->getId(),
            get_class( $node ),
            $this->id,
            $this->workflow->name,
            $this->workflow->version
          ),
          ezcWorkflowExecutionListener::DEBUG
        );

        return true;
    }

    /**
     * Add a variable that an (input) node is waiting for.
     *
     * @todo internal only?
     * @param ezcWorkflowNode $node
     * @param string $variableName
     * @param ezcWorkflowCondition $condition
     */
    public function addWaitingFor( ezcWorkflowNode $node, $variableName, ezcWorkflowCondition $condition )
    {
        if ( !isset( $this->waitingFor[$variableName] ) )
        {
            $this->waitingFor[$variableName] = array(
              'node' => $node->getId(),
              'condition' => $condition
            );
        }
    }

    /**
     * Returns the variables that (input) nodes are waiting for.
     *
     * @return array
     */
    public function getWaitingFor()
    {
        return $this->waitingFor;
    }

    /**
     * Start a new thread and returns the id of the new thread.
     *
     * @param integer $parentId The id of the parent thread.
     * @param integer $numSiblings The number of threads that are started by the same node.
     * @return integer
     */
    public function startThread( $parentId = null, $numSiblings = 1 )
    {
        $this->threads[$this->nextThreadId] = array(
          'parentId' => $parentId,
          'numSiblings' => $numSiblings
        );

        $this->notifyListeners(
          sprintf(
            'Started thread #%d (%s%d sibling(s)) for execution #%d of workflow "%s" (version %d).',

            $this->nextThreadId,
            $parentId != null ? 'parent: ' . $parentId . ', ' : '',
            $numSiblings,
            $this->id,
            $this->workflow->name,
            $this->workflow->version
          ),
          ezcWorkflowExecutionListener::DEBUG
        );

        return $this->nextThreadId++;
    }

    /**
     * Ends the thread with id $threadId
     *
     * @param  integer $threadId
     */
    public function endThread( $threadId )
    {
        if ( isset( $this->threads[$threadId] ) )
        {
            unset( $this->threads[$threadId] );

            $this->notifyListeners(
              sprintf(
                'Ended thread #%d for execution #%d of workflow "%s" (version %d).',

                $threadId,
                $this->id,
                $this->workflow->name,
                $this->workflow->version
              ),
              ezcWorkflowExecutionListener::DEBUG
            );
        }
        else
        {
            throw new ezcWorkflowExecutionException(
              sprintf(
                'There is no thread with id #%s',
                $threadId
              )
            );
        }
    }

    /**
     * Returns a new execution object for a sub workflow.
     *
     * @todo who and when is this calls?
     * @param  integer $id
     * @param  boolean $interactive
     * @return ezcWorkflowExecution
     */
    public function getSubExecution( $id = null, $interactive = true )
    {
        if ( $interactive )
        {
            $execution = $this->doGetSubExecution();
        }
        else
        {
            $execution = new ezcWorkflowExecutionNonInteractive;
        }

        if ( $id !== NULL )
        {
            $execution->resume( $id );
        }

        foreach ( $this->listeners as $listener )
        {
            $execution->addListener( $listener );
        }

        return $execution;
    }

    /**
     * Returns the number of siblings for a given thread.
     *
     * @param  integer $threadId The id of the thread for which to return the number of siblings.
     * @return integer
     */
    public function getNumSiblingThreads( $threadId )
    {
        if ( isset( $this->threads[$threadId] ) )
        {
            return $this->threads[$threadId]['numSiblings'];
        }
        else
        {
            return false;
        }
    }

    /**
     * Returns the id of the parent thread for a given thread.
     *
     * @param  integer $threadId The id of the thread for which to return the parent thread id.
     * @return integer
     */
    public function getParentThreadId( $threadId )
    {
        if ( isset( $this->threads[$threadId] ) )
        {
            return $this->threads[$threadId]['parentId'];
        }
        else
        {
            return false;
        }
    }

    /**
     * Adds a listener to this execution.
     *
     * @param ezcWorkflowExecutionListener $listener
     * @return boolean true when the listener was added, false otherwise.
     */
    public function addListener( ezcWorkflowExecutionListener $listener )
    {
        if ( ezcWorkflowUtil::findObject( $this->listeners, $listener ) !== false )
        {
            return false;
        }

        $this->listeners[] = $listener;

        return true;
    }

    /**
     * Removes a listener from this execution.
     *
     * @param ezcWorkflowExecutionListener $listener
     * @return boolean true when the listener was removed, false otherwise.
     */
    public function removeListener( ezcWorkflowExecutionListener $listener )
    {
        $index = ezcWorkflowUtil::findObject( $this->listeners, $listener );

        if ( $index === false )
        {
            return false;
        }

        unset( $this->listeners[$index] );

        return true;
    }

    /**
     * Notify listeners.
     *
     * @param string  $message
     * @param integer $type
     */
    protected function notifyListeners( $message, $type = ezcWorkflowExecutionListener::INFO )
    {
        foreach ( $this->listeners as $listener )
        {
            $listener->notify( $message, $type );
        }
    }

    /**
     * Returns the execution ID.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a variable.
     *
     * @param string $variableName
     */
    public function getVariable( $variableName )
    {
        if ( isset( $this->variables[$variableName] ) )
        {
            return $this->variables[$variableName];
        }
        else
        {
            throw new ezcWorkflowExecutionException(
              sprintf(
                'Variable "%s" does not exist.',
                $variableName
              )
            );
        }
    }

    /**
     * Returns the variables.
     *
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * Sets a variable.
     *
     * @param string $variableName
     * @param mixed $value
     */
    public function setVariable( $variableName, $value )
    {
        $this->variables[$variableName] = $value;

        $this->notifyListeners(
          sprintf(
            'Set variable "%s" to "%s" for execution #%d of workflow "%s" (version %d).',

            $variableName,
            ezcWorkflowUtil::variableToString( $value ),
            $this->id,
            $this->workflow->name,
            $this->workflow->version
          ),
          ezcWorkflowExecutionListener::DEBUG
        );
    }

    /**
     * Sets the variables.
     *
     * @param array $variables
     */
    public function setVariables( Array $variables )
    {
        $this->variables = array();

        foreach ( $variables as $variableName => $value )
        {
            $this->setVariable( $variableName, $value );
        }
    }

    /**
     * Unsets a variable.
     *
     * @param string $variableName
     */
    public function unsetVariable( $variableName )
    {
        if ( isset( $this->variables[$variableName] ) )
        {
            unset( $this->variables[$variableName] );

            $this->notifyListeners(
              sprintf(
                'Unset variable "%s" for execution #%d of workflow "%s" (version %d).',

                $variableName,
                $this->id,
                $this->workflow->name,
                $this->workflow->version
              ),
              ezcWorkflowExecutionListener::DEBUG
            );
        }
    }

    /**
     * Returns true when the workflow execution has ended.
     *
     * @return boolean
     */
    public function hasEnded()
    {
        return $this->ended;
    }

    /**
     * Returns true when the workflow execution has been resumed.
     *
     * @return boolean
     */
    public function isResumed()
    {
        return $this->resumed;
    }

    /**
     * Returns true when the workflow execution has been suspended.
     *
     * @return boolean
     */
    public function isSuspended()
    {
        return $this->suspended;
    }

    /**
     * Load data from variable handlers and 
     * merge it with the current execution data.
     */
    protected function loadFromVariableHandlers()
    {
        foreach ( $this->workflow->getVariableHandlers() as $variableName => $className )
        {
            $object = new $className;
            $this->setVariable( $variableName, $object->load( $variableName ) );
        }
    }

    /**
     * Save data to execution data handlers.
     */
    protected function saveToVariableHandlers()
    {
        foreach ( $this->workflow->getVariableHandlers() as $variableName => $className )
        {
            if ( isset( $this->variables[$variableName] ) )
            {
                $object = new $className;
                $object->save( $variableName, $this->variables[$variableName] );
            }
        }
    }

    /**
     * Start workflow execution.
     *
     * @param  integer $parentId
     */
    abstract protected function doStart( $parentId );

    /**
     * Suspend workflow execution.
     */
    abstract protected function doSuspend();

    /**
     * Resume workflow execution.
     *
     * @param integer $executionId  ID of the execution to resume.
     */
    abstract protected function doResume( $executionId );

    /**
     * End workflow execution.
     */
    abstract protected function doEnd();

    /**
     * Returns a new execution object for a sub workflow.
     *
     * @return ezcWorkflowExecution
     */
    abstract protected function doGetSubExecution();
}
?>
