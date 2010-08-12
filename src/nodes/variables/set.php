<?php
/**
 * File containing the ezcWorkflowNodeVariableSet class.
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
 * An object of the ezcWorkflowNodeVariableSet class sets the specified workflow variable to
 * a given value.
 *
 * <code>
 * <?php
 * $set = new ezcWorkflowNodeVariableSet( array( 'variable name' = > $value ) );
 * ?>
 * </code>
 *
 * Incoming nodes: 1
 * Outgoing nodes: 1
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowNodeVariableSet extends ezcWorkflowNode
{
    /**
     * Constructs a new variable set node with the configuration $configuration.
     *
     * The configuration is an array of keys and values of the format:
     * array( 'workflow variable name' => value )
     *
     * @param mixed $configuration
     * @throws ezcBaseValueException
     */
    public function __construct( $configuration = '' )
    {
        if ( !is_array( $configuration ) )
        {
            throw new ezcBaseValueException(
              'configuration', $configuration, 'array'
            );
        }

        parent::__construct( $configuration );
    }

    /**
     * Executes this by setting all the variables specified by the
     * configuration.
     *
     * @param ezcWorkflowExecution $execution
     * @return boolean true when the node finished execution,
     *                 and false otherwise
     * @ignore
     */
    public function execute( ezcWorkflowExecution $execution )
    {
        foreach ( $this->configuration as $variable => $value )
        {
            $execution->setVariable( $variable, $value );
        }

        $this->activateNode( $execution, $this->outNodes[0] );

        return parent::execute( $execution );
    }

    /**
     * Generate node configuration from XML representation.
     *
     * @param DOMElement $element
     * @return array
     * @ignore
     */
    public static function configurationFromXML( DOMElement $element )
    {
        $configuration = array();

        foreach ( $element->getElementsByTagName( 'variable' ) as $variable )
        {
            $configuration[$variable->getAttribute( 'name' )] = ezcWorkflowDefinitionStorageXml::xmlToVariable(
              ezcWorkflowUtil::getChildNode( $variable )
            );
        }

        return $configuration;
    }

    /**
     * Generate XML representation of this node's configuration.
     *
     * @param DOMElement $element
     * @ignore
     */
    public function configurationToXML( DOMElement $element )
    {
        foreach ( $this->configuration as $variable => $value )
        {
            $variableXml = $element->appendChild(
              $element->ownerDocument->createElement( 'variable' )
            );

            $variableXml->setAttribute( 'name', $variable );

            $variableXml->appendChild(
              ezcWorkflowDefinitionStorageXml::variableToXml(
                $value, $element->ownerDocument
              )
            );
        }
    }

    /**
     * Returns a textual representation of this node.
     *
     * @return string
     * @ignore
     */
    public function __toString()
    {
        $buffer = array();

        foreach ( $this->configuration as $variable => $value )
        {
            $buffer[] = $variable . ' = ' . ezcWorkflowUtil::variableToString( $value );
        }

        return implode( ', ', $buffer );
    }
}
?>
