<?php
/**
 * File containing the ezcWorkflowConditionComparison class.
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
 * Abstract base class for comparison conditions.
 *
 * @package Workflow
 * @version //autogen//
 */
abstract class ezcWorkflowConditionComparison implements ezcWorkflowCondition
{
    /**
     * Textual representation of the comparison operator.
     *
     * @var mixed
     */
    protected $operator = '';

    /**
     * The value that this condition compares against.
     *
     * @var mixed
     */
    protected $value;

    /**
     * Constructs a new comparison condition.
     *
     * Implemenations will compare $value to the value provided to evaluate().
     *
     * @param  mixed  $value
     */
    public function __construct( $value = null )
    {
        $this->value = $value;
    }

    /**
     * Returns the value that this condition compares against.
     *
     * @return mixed
     * @ignore
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value that this condition compares against.
     *
     * @param mixed $value
     * @ignore
     */
    public function setValue( $value )
    {
        $this->value = $value;
    }

    /**
     * Returns the operator.
     *
     * @return string
     * @ignore
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Returns a textual representation of this condition.
     *
     * @return string
     * @ignore
     */
    public function __toString()
    {
        return $this->operator . ' ' . $this->value;
    }
}
?>
