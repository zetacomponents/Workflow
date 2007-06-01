<?php
/**
 * File containing the ezcWorkflowConditionBooleanSet class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
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
     * @var array
     */
    protected $conditions;

    /**
     * @var string
     */
    protected $concatenation;

    /**
     * Constructs a new boolean set with the conditions $conditions.
     *
     * The format of $conditions must be array( ezcWorkflowCondition )
     *
     * @param  Array $conditions
     * @throws ezcWorkflowDefinitionStorageException
     */
    public function __construct( Array $conditions )
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

            $string .= (string) $condition;
        }

        return $string . ' )';
    }
}
?>
