<?php
/**
 * File containing the ezcWorkflowConditionNot class.
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
 * Boolean NOT.
 *
 * An object of the ezcWorkflowConditionNot decorates an ezcWorkflowCondition object
 * and negates its expression.
 *
 * <code>
 * <?php
 * $notNondition = new ezcWorkflowConditionNot( $condition ) ;
 * ?>
 * </code>
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowConditionNot implements ezcWorkflowCondition
{
    /**
     * Holds the expression to negate.
     *
     * @var ezcWorkflowCondition
     */
    protected $condition;

    /**
     * Constructs a new not condition on $condition.
     *
     * @param  ezcWorkflowCondition $condition
     */
    public function __construct( ezcWorkflowCondition $condition )
    {
        $this->condition = $condition;
    }

    /**
     * Evaluates this condition with the value $value and returns true if the condition holds.
     *
     * If the condition does not hold false is returned.
     *
     * @param  mixed $value
     * @return boolean true when the condition holds, false otherwise.
     * @ignore
     */
    public function evaluate( $value )
    {
        return !$this->condition->evaluate( $value );
    }

    /**
     * Returns the condition that is negated.
     *
     * @return ezcWorkflowCondition
     * @ignore
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * Returns a textual representation of this condition.
     *
     * @return string
     * @ignore
     */
    public function __toString()
    {
        return '! ' . $this->condition;
    }
}
?>
