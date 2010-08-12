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
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

require_once 'case.php';

/**
 * @package Workflow
 * @subpackage Tests
 */
class ezcWorkflowVisitorVisualizationTest extends ezcWorkflowTestCase
{
    protected $visitor;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( 'ezcWorkflowVisitorVisualizationTest' );
    }

    protected function setUp()
    {
        parent::setUp();

        $this->visitor = new ezcWorkflowVisitorVisualization;
    }

    /**
     * @dataProvider workflowNameProvider
     */
    public function testVisualizeWorkflow($workflowName)
    {
        $setupMethod = 'setUp' . $workflowName;

        $this->$setupMethod();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( $workflowName ),
          (string)$this->visitor
        );
    }

    public function testBug13467()
    {
        $this->workflow = $this->xmlStorage->loadByName( 'bug13467' );
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'bug13467' ),
          (string)$this->visitor
        );
    }

    public function testHighlightedStartNode()
    {
        $this->visitor->options['highlightedNodes'] = array( 1 );

        $this->setUpStartEnd();
        $this->workflow->accept( $this->visitor );

        $this->assertEquals(
          $this->readExpected( 'StartEnd2' ),
          (string)$this->visitor
        );
    }

    public function testProperties()
    {
        try
        {
            $foo = $this->visitor->foo;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            $this->assertEquals( "No such property name 'foo'.", $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcBasePropertyNotFoundException to be thrown.' );
    }

    public function testProperties2()
    {
        try
        {
            $this->visitor->foo = 'foo';
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            $this->assertEquals( 'No such property name \'foo\'.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcBasePropertyNotFoundException to be thrown.' );
    }

    public function testOptions()
    {
        $this->assertTrue( isset( $this->visitor->options['colorHighlighted'] ) );
        $this->assertTrue( isset( $this->visitor->options['colorNormal'] ) );
        $this->assertFalse( isset( $this->visitor->foo ) );

        $this->assertEquals( '#cc0000', $this->visitor->options['colorHighlighted'] );
        $this->assertEquals( '#2e3436', $this->visitor->options['colorNormal'] );

        $this->visitor->options['colorHighlighted'] = '#2e3436';
        $this->visitor->options['colorNormal'] = '#cc0000';

        $this->assertEquals( '#2e3436', $this->visitor->options['colorHighlighted'] );
        $this->assertEquals( '#cc0000', $this->visitor->options['colorNormal'] );
    }

    public function testOptions2()
    {
        try
        {
            $this->visitor->options['colorHighlighted'] = null;
        }
        catch ( ezcBaseValueException $e )
        {
            $this->assertEquals( 'The value \'\' that you were trying to assign to setting \'colorHighlighted\' is invalid. Allowed values are: string.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcBaseValueException to be thrown.' );
    }

    public function testOptions3()
    {
        try
        {
            $this->visitor->options = null;
        }
        catch ( ezcBaseValueException $e )
        {
            $this->assertEquals( 'The value \'\' that you were trying to assign to setting \'options\' is invalid. Allowed values are: ezcWorkflowVisitorVisualizationOptions.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcBaseValueException to be thrown.' );
    }

    public function testOptions4()
    {
        try
        {
            $this->visitor->options['highlightedNodes'] = null;
        }
        catch ( ezcBaseValueException $e )
        {
            $this->assertEquals( 'The value \'\' that you were trying to assign to setting \'highlightedNodes\' is invalid. Allowed values are: array.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcBaseValueException to be thrown.' );
    }

    public function testOptions5()
    {
        try
        {
            $this->visitor->options['foo'] = null;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            $this->assertEquals( 'No such property name \'foo\'.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcBasePropertyNotFoundException to be thrown.' );
    }

    protected function readExpected( $name )
    {
        return file_get_contents(
          dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR. $name . '.dot'
        );
    }
}
?>
