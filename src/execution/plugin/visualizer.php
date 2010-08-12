<?php
/**
 * File containing the ezcWorkflowExecutionVisualizerPlugin class.
 *
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 * 
 *   http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 *
 * @package Workflow
 * @version //autogen//
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

/**
 * Execution plugin that visualizes the execution.
 *
 * <code>
 * <?php
 * $db         = ezcDbFactory::create( 'mysql://test@localhost/test' );
 * $definition = new ezcWorkflowDatabaseDefinitionStorage( $db );
 * $workflow   = $definition->loadByName( 'Test' );
 * $execution  = new ezcWorkflowDatabaseExecution( $db );
 *
 * $execution->workflow = $workflow;
 * $execution->addPlugin( new ezcWorkflowExecutionVisualizerPlugin( '/tmp' ) );
 * $execution->start();
 * ?>
 * </code>
 *
 * @property ezcWorkflowExecutionVisualizerPluginOptions $options
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowExecutionVisualizerPlugin extends ezcWorkflowExecutionPlugin
{
    /**
     * Filename counter.
     *
     * @var integer
     */
    protected $fileCounter = 0;

    /**
     * Properties.
     *
     * @var array(string=>mixed)
     */
    protected $properties = array();

    /**
     * Constructor.
     *
     * @param string $directory The directory to which the DOT files are written.
     */
    public function __construct( $directory )
    {
        $this->options = new ezcWorkflowExecutionVisualizerPluginOptions;
        $this->options['directory'] = $directory;
    }

    /**
     * Property get access.
     *
     * @throws ezcBasePropertyNotFoundException
     *         If the given property could not be found.
     * @param string $propertyName
     * @ignore
     */
    public function __get( $propertyName )
    {
        if ( $this->__isset( $propertyName ) )
        {
            return $this->properties[$propertyName];
        }
        throw new ezcBasePropertyNotFoundException( $propertyName );
    }

    /**
     * Property set access.
     *
     * @throws ezcBasePropertyNotFoundException
     * @param string $propertyName
     * @param string $propertyValue
     * @ignore
     */
    public function __set( $propertyName, $propertyValue )
    {
        switch ( $propertyName )
        {
            case 'options':
                if ( !( $propertyValue instanceof ezcWorkflowExecutionVisualizerPluginOptions ) )
                {
                    throw new ezcBaseValueException(
                        $propertyName,
                        $propertyValue,
                        'ezcWorkflowExecutionVisualizerPluginOptions'
                    );
                }
                break;
            default:
                throw new ezcBasePropertyNotFoundException( $propertyName );
        }
        $this->properties[$propertyName] = $propertyValue;
    }

    /**
     * Property isset access.
     *
     * @param string $propertyName
     * @return bool
     * @ignore
     */
    public function __isset( $propertyName )
    {
        return array_key_exists( $propertyName, $this->properties );
    }

    /**
     * Called after a node has been activated.
     *
     * @param ezcWorkflowExecution $execution
     * @param ezcWorkflowNode      $node
     */
    public function afterNodeActivated( ezcWorkflowExecution $execution, ezcWorkflowNode $node )
    {
        $this->visualize( $execution );
    }

    /**
     * Called after a node has been executed.
     *
     * @param ezcWorkflowExecution $execution
     * @param ezcWorkflowNode      $node
     */
    public function afterNodeExecuted( ezcWorkflowExecution $execution, ezcWorkflowNode $node )
    {
        $this->visualize( $execution );
    }

    /**
     * Visualizes the current state of the workflow execution.
     *
     * @param ezcWorkflowExecution $execution
     */
    protected function visualize( ezcWorkflowExecution $execution )
    {
        $activatedNodes = array();

        foreach ( $execution->getActivatedNodes() as $node )
        {
            $activatedNodes[] = $node->getId();
        }

        if ( $this->options['includeVariables'] )
        {
            $variables = $execution->getVariables();
        }
        else
        {
            $variables = array();
        }

        $visitor = new ezcWorkflowVisitorVisualization;
        $visitor->options['highlightedNodes']  = $activatedNodes;
        $visitor->options['workflowVariables'] = $variables;

        $execution->workflow->accept( $visitor );

        file_put_contents(
          sprintf(
            '%s%s%s_%03d_%03d.dot',

            $this->options['directory'],
            DIRECTORY_SEPARATOR,
            $execution->workflow->name,
            $execution->getId(),
            ++$this->fileCounter
          ),
          $visitor
        );
    }
}
?>
