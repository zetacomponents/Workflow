<?php
/**
 * File containing the ezcWorkflowVariableHandler interface.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Interface for variable handlers.
 *
 * @package Workflow
 * @version //autogen//
 */
interface ezcWorkflowVariableHandler
{
    /**
     * Load the variable $variableName that is handled by this handler.
     *
     * @param string $variableName
     */
    public function load( $variableName );

    /**
     * Save the variable $variableName that is handled by the variable handler
     * with the value $value.
     *
     * @param string $variableName
     * @param mixed  $value
     */
    public function save( $variableName, $value );
}
?>
