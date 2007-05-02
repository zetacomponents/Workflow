<?php
/**
 * File containing the ezcWorkflowVisitor interface.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Interface for visitor implementations that want to process
 * a workflow using the Visitor design pattern.
 *
 * @package Workflow
 * @version //autogen//
 */
interface ezcWorkflowVisitor
{
    /**
     * @param ezcWorkflowVisitable $visitable
     * @return boolean
     */
    public function visit( ezcWorkflowVisitable $visitable );
}
?>
