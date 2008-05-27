<?php
/**
 * @package Workflow
 * @subpackage Tests
 * @version //autogentag//
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

require_once 'definition_xml_test.php';
require_once 'execution_test.php';
require_once 'execution_listener_test.php';
require_once 'execution_plugin_test.php';
require_once 'execution_plugin_visualizer_test.php';
require_once 'workflow_test.php';
require_once 'node_test.php';
require_once 'condition_test.php';
require_once 'visitor_visualization_test.php';

/**
 * @package Workflow
 * @subpackage Tests
 */
class ezcWorkflowSuite extends PHPUnit_Framework_TestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( 'Workflow' );

        $this->addTest( ezcWorkflowDefinitionStorageXmlTest::suite() );
        $this->addTest( ezcWorkflowExecutionTest::suite() );
        $this->addTest( ezcWorkflowExecutionListenerTest::suite() );
        #$this->addTest( ezcWorkflowExecutionPluginTest::suite() );
        $this->addTest( ezcWorkflowExecutionPluginVisualizerTest::suite() );
        $this->addTest( ezcWorkflowTest::suite() );
        $this->addTest( ezcWorkflowNodeTest::suite() );
        $this->addTest( ezcWorkflowConditionTest::suite() );
        $this->addTest( ezcWorkflowVisitorVisualizationTest::suite() );
    }

    public static function suite()
    {
        return new ezcWorkflowSuite;
    }
}
?>
