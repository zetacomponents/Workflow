<?php
/**
 * @package Workflow
 * @subpackage Tests
 * @version //autogentag//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

ezcTestRunner::addFileToFilter( __FILE__ );

/**
 * @package Workflow
 * @subpackage Tests
 */
class ezcWorkflowTestVariableHandler implements ezcWorkflowVariableHandler
{
    protected $storage = array( 'foo' => 'bar' );

    public function load( $variableName )
    {
        return $this->storage[$variableName];
    }

    public function save( $variableName, $value )
    {
        $this->storage[$variableName] = $value;
    }
}
?>
