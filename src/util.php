<?php
/**
 * File containing the ezcWorkflowUtil class.
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
 * Workflow utility methods.
 *
 * @package Workflow
 * @version //autogen//
 * @ignore
 */
abstract class ezcWorkflowUtil
{
    /**
     * Finds the object $needle in an array of objects
     * and returns the position in the $haystack
     *
     * @param array $haystack The array of objects to search.
     * @param object $needle The object to search for.
     * @return mixed The index position at which the object is located in the array, false otherwise.
     */
    public static function findObject( array $haystack, $needle )
    {
        $keys  = array_keys( $haystack );
        $count = count( $keys );

        for ( $i = 0; $i < $count; $i++ )
        {
            if ( $haystack[$keys[$i]] === $needle )
            {
                return $keys[$i];
            }
        }

        return false;
    }

    /**
     * Returns the default configuration for a node class.
     *
     * @param string $className
     * @return mixed
     */
    public static function getDefaultConfiguration( $className )
    {
        $configuration = null;

        $class    = new ReflectionClass( $className );
        $defaults = $class->getDefaultProperties();

        if ( isset( $defaults['configuration'] ) )
        {
            $configuration = $defaults['configuration'];
        }

        return $configuration;
    }

    /**
     * Wrapper around DOMNode->childNodes that filters DOMText (whitespace)
     * nodes.
     *
     * @param  DOMNode $node
     * @return array
     */
    public static function getChildNodes( DOMNode $node )
    {
        $childNodes = array();

        foreach ( $node->childNodes as $childNode )
        {
            if ( !$childNode instanceof DOMText )
            {
                $childNodes[] = $childNode;
            }
        }

        return $childNodes;
    }

    /**
     * Wrapper around getChildNodes() that only returns the first node.
     *
     * @param  DOMNode $node
     * @return DOMNode
     */
    public static function getChildNode( DOMNode $node )
    {
        $childNodes = self::getChildNodes( $node );

        return $childNodes[0];
    }

    /**
     * Returns a compact textual representation of a PHP variable.
     *
     * @param mixed $variable
     * @return string
     */
    public static function variableToString( $variable )
    {
        if ( $variable === null )
        {
            return '<null>';
        }

        if ( $variable === true )
        {
            return '<true>';
        }

        if ( $variable === false )
        {
            return '<false>';
        }

        if ( is_array( $variable ) )
        {
            return '<array>';
        }

        if ( is_object( $variable ) )
        {
            return '<' . get_class( $variable ) . '>';
        }

        return $variable;
    }
}
?>
