<?php
/**
 * File containing the ezcWorkflowConditionXor class.
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
 * Boolean XOR.
 *
 * An object of the ezcWorkflowConditionXor class represents a boolean XOR expression. It
 * can hold an arbitrary number of ezcWorkflowCondition objects.
 *
 * <code>
 * <?php
 * $xor = new ezcWorkflowConditionXor( array ( $condition , ... ) );
 * ?>
 * </code>
 *
 * @package Workflow
 * @version //autogen//
 */
class ezcWorkflowConditionXor extends ezcWorkflowConditionBooleanSet
{
    /**
     * Textual representation of the concatenation.
     *
     * @var string
     */
    protected $concatenation = 'XOR';

    /**
     * Evaluates this condition with $value and returns true if the condition holds and false otherwise.
     *
     * @param  mixed $value
     * @return boolean true when the condition holds, false otherwise.
     * @ignore
     */
    public function evaluate( $value )
    {
        $result = false;

        foreach ( $this->conditions as $condition )
        {
            if ( $condition->evaluate( $value ) )
            {
                if ( $result )
                {
                    return false;
                }

                $result = true;
            }
        }

        return $result;
    }
}
?>
