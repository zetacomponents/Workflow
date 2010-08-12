<?php
/**
 * File containing the ezcWorkflowConditionVariables class.
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
 * Wrapper that applies a condition to two workflow variables.
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowConditionVariables implements ezcWorkflowCondition
{
    /**
     * The name of the first variable the condition is applied to.
     *
     * @var string
     */
    protected $variableNameA;

    /**
     * The name of the second variable the condition is applied to.
     *
     * @var string
     */
    protected $variableNameB;

    /**
     * The condition that is applied to the variable.
     *
     * @var ezcWorkflowCondition
     */
    protected $condition;

    /**
     * Constructor.
     *
     * @param  string $variableNameA
     * @param  string $variableNameB
     * @param  ezcWorkflowCondition $condition
     * @throws ezcBaseValueException
     */
    public function __construct( $variableNameA, $variableNameB, ezcWorkflowCondition $condition )
    {
        if ( !$condition instanceof ezcWorkflowConditionComparison )
        {
            throw new ezcBaseValueException(
              'condition',
              $condition,
              'ezcWorkflowConditionComparison'
            );
        }

        $this->variableNameA = $variableNameA;
        $this->variableNameB = $variableNameB;
        $this->condition     = $condition;
    }

    /**
     * Evaluates this condition.
     *
     * @param  mixed $value
     * @return boolean true when the condition holds, false otherwise.
     * @ignore
     */
    public function evaluate( $value )
    {
        if ( is_array( $value ) &&
             isset( $value[$this->variableNameA] ) &&
             isset( $value[$this->variableNameB] ) )
        {
            $this->condition->setValue( $value[$this->variableNameA] );
            return $this->condition->evaluate( $value[$this->variableNameB] );
        }
        else
        {
            return false;
        }
    }

    /**
     * Returns the condition.
     *
     * @return ezcWorkflowCondition
     * @ignore
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * Returns the names of the variables the condition is evaluated for.
     *
     * @return array
     * @ignore
     */
    public function getVariableNames()
    {
        return array( $this->variableNameA, $this->variableNameB );
    }

    /**
     * Returns a textual representation of this condition.
     *
     * @return string
     * @ignore
     */
    public function __toString()
    {
        return sprintf(
          '%s %s %s',

          $this->variableNameA,
          $this->condition->getOperator(),
          $this->variableNameB
        );
    }
}
?>
