<?php
/**
 * File containing the ezcWorkflowVisitable interface.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Interface for visitable workflow elements that can be visited
 * by ezcWorkflowVisitor implementations for processing using the
 * Visitor design pattern.
 *
 * @package Workflow
 * @version //autogen//
 */
interface ezcWorkflowVisitable
{
    /**
     * @param ezcWorkflowVisitor $visitor
     */
    public function accept( ezcWorkflowVisitor $visitor );
}
?>
