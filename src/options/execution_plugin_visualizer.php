<?php
/**
 * This file contains the ezcWorkflowExecutionVisualizerPluginOptions class.
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
 * @version //autogentag//
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @filesource
 */

/**
 * Options class for ezcWorkflowExecutionVisualizerPlugin.
 *
 * @property string $directory
 *           The directory to which the DOT files are written.
 * @property bool $includeVariables
 *           Whether or not to include workflow variables.
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowExecutionVisualizerPluginOptions extends ezcBaseOptions
{
    /**
     * Properties.
     *
     * @var array(string=>mixed)
     */
    protected $properties = array(
        'directory'        => null,
        'includeVariables' => true,
    );

    /**
     * Property write access.
     *
     * @param string $propertyName  Name of the property.
     * @param mixed  $propertyValue The value for the property.
     *
     * @throws ezcBasePropertyNotFoundException
     *         If the the desired property is not found.
     * @throws ezcBaseFileNotFoundException
     *         When the directory does not exist.
     * @throws ezcBaseFilePermissionException
     *         When the directory is not writable.
     * @ignore
     */
    public function __set( $propertyName, $propertyValue )
    {
        switch ( $propertyName )
        {
            case 'directory':
                if ( !is_string( $propertyValue ) )
                {
                    throw new ezcBaseValueException(
                        $propertyName,
                        $propertyValue,
                        'string'
                    );
                }

                if ( !is_dir( $propertyValue ) )
                {
                    throw new ezcBaseFileNotFoundException( $propertyValue, 'directory' );
                }

                if ( !is_writable( $propertyValue ) )
                {
                    // @codeCoverageIgnoreStart
                    throw new ezcBaseFilePermissionException( $propertyValue, ezcBaseFileException::WRITE );
                    // @codeCoverageIgnoreEnd
                }
                break;
            case 'includeVariables':
                if ( !is_bool( $propertyValue ) )
                {
                    throw new ezcBaseValueException(
                        $propertyName,
                        $propertyValue,
                        'bool'
                    );
                }
                break;
            default:
                throw new ezcBasePropertyNotFoundException( $propertyName );
        }
        $this->properties[$propertyName] = $propertyValue;
    }
}
?>
