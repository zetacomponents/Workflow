<?php
/**
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
 * @subpackage Tests
 * @version //autogentag//
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
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
        $this->addTest( ezcWorkflowExecutionPluginTest::suite() );
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
