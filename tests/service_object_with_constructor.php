<?php
/**
 * File containing the ServiceObjectWithConstructor class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

ezcTestRunner::addFileToFilter( __FILE__ );

/**
 * A service object that has a constructor.
 *
 * @package Workflow
 * @subpackage Tests
 * @version //autogen//
 */
class ServiceObjectWithConstructor implements ezcWorkflowServiceObject
{
    /**
     * Constructor.
     *
     * @param 
     */
    public function __construct()
    {
    }

    /**
     * Executes the business logic of this service object.
     *
     * @param ezcWorkflowExecution $execution
     */
    public function execute( ezcWorkflowExecution $execution )
    {
    }

    /**
     * Returns a textual representation of this service object.
     *
     * @return string
     */
    public function __toString()
    {
    }
}
?>
