<?php
/**
 * File containing the ezcWorkflowInvalidInputException class.
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
 * This exception will be thrown when an error occurs
 * during input validation in an input node.
 *
 * @property-read array $errors The input validation error(s).
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowInvalidInputException extends ezcWorkflowExecutionException
{
    /**
     * Container to hold the properties
     *
     * @var array(string=>mixed)
     */
    protected $properties = array(
      'errors' => array(),
    );

    /**
     * Constructor.
     *
     * @param array $message
     */
    public function __construct( $message )
    {
        $this->properties['errors'] = $message;

        $messages = array();

        foreach ( $message as $variable => $condition )
        {
            $messages[] = $variable . ' ' . $condition;
        }

        parent::__construct( join( "\n", $messages ) );
    }

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
            case 'errors':
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
     * @throws ezcBasePropertyPermissionException
     *         If there is a write access to errors.
     * @ignore
     */
    public function __set( $propertyName, $val )
    {
        switch ( $propertyName )
        {
            case 'errors':
                throw new ezcBasePropertyPermissionException( $propertyName, ezcBasePropertyPermissionException::WRITE );
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
            case 'errors':
                return true;
        }

        return false;
    }
}
?>
