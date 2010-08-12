<?php
/**
 * File containing the ezcWorkflowConditionBooleanSet class.
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
 * Abstract base class for boolean sets of conditions like AND, OR and XOR.
 *
 * @package Workflow
 * @version //autogen//
 */
abstract class ezcWorkflowConditionBooleanSet implements ezcWorkflowCondition
{
    /**
     * Array of ezcWorkflowConditions
     *
     * @var array
     */
    protected $conditions;

    /**
     * String representation of the concatination.
     *
     * Used by the __toString() methods.
     *
     * @var string
     */
    protected $concatenation;

    /**
     * Constructs a new boolean set with the conditions $conditions.
     *
     * The format of $conditions must be array( ezcWorkflowCondition )
     *
     * @param array $conditions
     * @throws ezcWorkflowDefinitionStorageException
     */
    public function __construct( array $conditions )
    {
        foreach ( $conditions as $condition )
        {
            if ( !$condition instanceof ezcWorkflowCondition )
            {
                throw new ezcWorkflowDefinitionStorageException(
                  'Array does not contain (only) ezcWorkflowCondition objects.'
                );
            }

            $this->conditions[] = $condition;
        }
    }

    /**
     * Returns the conditions in this boolean set.
     *
     * @return ezcWorkflowCondition[]
     * @ignore
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * Returns a textual representation of this condition.
     *
     * @return string
     * @ignore
     */
    public function __toString()
    {
        $string = '( ';

        foreach ( $this->conditions as $condition )
        {
            if ( $string != '( ' )
            {
                $string .= ' ' . $this->concatenation . ' ';
            }

            $string .= $condition;
        }

        return $string . ' )';
    }
}
?>
