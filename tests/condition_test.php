<?php
/**
 * @package Workflow
 * @subpackage Tests
 * @version //autogentag//
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * @package Workflow
 * @subpackage Tests
 */
class ezcWorkflowConditionTest extends ezcTestCase
{
    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( 'ezcWorkflowConditionTest' );
    }

    public function testInArray()
    {
        $condition = new ezcWorkflowConditionInArray( array( '1', 2, 3 ) );
        $this->assertTrue( $condition->evaluate( 1 ) );
        $this->assertFalse( $condition->evaluate( 4 ) );
        $this->assertEquals( "in array('1', 2, 3)", (string)$condition );
    }

    public function testIsAnything()
    {
        $condition = new ezcWorkflowConditionIsAnything;

        $this->assertTrue( $condition->evaluate( null ) );
        $this->assertEquals( 'is anything', (string)$condition );
    }

    public function testIsArray()
    {
        $condition = new ezcWorkflowConditionIsArray;

        $this->assertTrue( $condition->evaluate( array() ) );
        $this->assertFalse( $condition->evaluate( null ) );
        $this->assertEquals( 'is array', (string)$condition );
    }

    public function testIsBool()
    {
        $condition = new ezcWorkflowConditionIsBool;

        $this->assertTrue( $condition->evaluate( true ) );
        $this->assertTrue( $condition->evaluate( false ) );
        $this->assertFalse( $condition->evaluate( null ) );
        $this->assertEquals( 'is bool', (string)$condition );
    }

    public function testIsTrue()
    {
        $condition = new ezcWorkflowConditionIsTrue;

        $this->assertTrue( $condition->evaluate( true ) );
        $this->assertFalse( $condition->evaluate( false ) );
        $this->assertEquals( 'is true', (string)$condition );
    }

    public function testIsFalse()
    {
        $condition = new ezcWorkflowConditionIsFalse;

        $this->assertFalse( $condition->evaluate( true ) );
        $this->assertTrue( $condition->evaluate( false ) );
        $this->assertEquals( 'is false', (string)$condition );
    }

    public function testIsFloat()
    {
        $condition = new ezcWorkflowConditionIsFloat;

        $this->assertTrue( $condition->evaluate( 0.0 ) );
        $this->assertFalse( $condition->evaluate( null ) );
        $this->assertEquals( 'is float', (string)$condition );
    }

    public function testIsInteger()
    {
        $condition = new ezcWorkflowConditionIsInteger;

        $this->assertTrue( $condition->evaluate( 0 ) );
        $this->assertFalse( $condition->evaluate( null ) );
        $this->assertEquals( 'is integer', (string)$condition );
    }

    public function testIsObject()
    {
        $condition = new ezcWorkflowConditionIsObject;

        $this->assertTrue( $condition->evaluate( new StdClass ) );
        $this->assertFalse( $condition->evaluate( null ) );
        $this->assertEquals( 'is object', (string)$condition );
    }

    public function testIsString()
    {
        $condition = new ezcWorkflowConditionIsString;

        $this->assertTrue( $condition->evaluate( '' ) );
        $this->assertFalse( $condition->evaluate( null ) );
        $this->assertEquals( 'is string', (string)$condition );
    }

    public function testIsEqual()
    {
        $condition = new ezcWorkflowConditionIsEqual( 2204 );

        $this->assertTrue( $condition->evaluate( 2204 ) );
        $this->assertFalse( $condition->evaluate( 1978 ) );
        $this->assertEquals( '== 2204', (string)$condition );
    }

    public function testIsNotEqual()
    {
        $condition = new ezcWorkflowConditionIsNotEqual( 2204 );

        $this->assertTrue( $condition->evaluate( 1978 ) );
        $this->assertFalse( $condition->evaluate( 2204 ) );
        $this->assertEquals( '!= 2204', (string)$condition );
    }

    public function testIsLessThan()
    {
        $condition = new ezcWorkflowConditionIsLessThan( 2204 );

        $this->assertTrue( $condition->evaluate( 1978 ) );
        $this->assertFalse( $condition->evaluate( 2204 ) );
        $this->assertEquals( '< 2204', (string)$condition );
    }

    public function testIsNotLessThan()
    {
        $condition = new ezcWorkflowConditionNot(
          new ezcWorkflowConditionIsLessThan( 2204 )
        );

        $this->assertTrue( $condition->evaluate( 2204 ) );
        $this->assertFalse( $condition->evaluate( 1978 ) );
        $this->assertEquals( '! < 2204', (string)$condition );
        $this->assertType( 'ezcWorkflowConditionIsLessThan', $condition->getCondition() );
    }

    public function testIsGreaterThan()
    {
        $condition = new ezcWorkflowConditionIsGreaterThan( 1978 );

        $this->assertTrue( $condition->evaluate( 2204 ) );
        $this->assertFalse( $condition->evaluate( 1978 ) );
        $this->assertEquals( '> 1978', (string)$condition );
    }

    public function testIsNotGreaterThan()
    {
        $condition = new ezcWorkflowConditionNot(
          new ezcWorkflowConditionIsGreaterThan( 1978 )
        );

        $this->assertTrue( $condition->evaluate( 1978 ) );
        $this->assertFalse( $condition->evaluate( 2204 ) );
        $this->assertEquals( '! > 1978', (string)$condition );
        $this->assertType( 'ezcWorkflowConditionIsGreaterThan', $condition->getCondition() );
    }

    public function testIsEqualOrGreaterThan()
    {
        $condition = new ezcWorkflowConditionIsEqualOrGreaterThan( 1 );

        $this->assertTrue( $condition->evaluate( 1 ) );
        $this->assertTrue( $condition->evaluate( 2 ) );
        $this->assertFalse( $condition->evaluate( 0 ) );
        $this->assertEquals( '>= 1', (string)$condition );
    }

    public function testIsEqualOrLessThan()
    {
        $condition = new ezcWorkflowConditionIsEqualOrLessThan( 1 );

        $this->assertTrue( $condition->evaluate( 1 ) );
        $this->assertTrue( $condition->evaluate( 0 ) );
        $this->assertFalse( $condition->evaluate( 2 ) );
        $this->assertEquals( '<= 1', (string)$condition );
    }

    public function testVariable()
    {
        $condition = new ezcWorkflowConditionVariable(
          'foo',
          new ezcWorkflowConditionIsAnything
        );

        $this->assertTrue( $condition->evaluate( array( 'foo' => 'bar' ) ) );
        $this->assertFalse( $condition->evaluate( array( 'bar' => 'foo' ) ) );
    }

    public function testVariables()
    {
        $condition = new ezcWorkflowConditionVariables(
          'foo',
          'bar',
          new ezcWorkflowConditionIsEqual
        );

        $this->assertTrue( $condition->evaluate( array( 'foo' => 'baz', 'bar' => 'baz' ) ) );
        $this->assertFalse( $condition->evaluate( array( 'foo' => 'bar', 'bar' => 'foo' ) ) );
    }

    /**
     * @expectedException ezcBaseValueException
     */
    public function testVariables2()
    {
        $condition = new ezcWorkflowConditionVariables(
          'foo',
          'bar',
          new ezcWorkflowConditionIsAnything
        );
    }

    public function testVariables3()
    {
        $condition = new ezcWorkflowConditionVariables(
          'foo',
          'bar',
          new ezcWorkflowConditionIsEqual
        );

        $this->assertFalse( $condition->evaluate( array() ) );
    }

    public function testAnd()
    {
        $true = new ezcWorkflowConditionIsTrue;

        $condition = new ezcWorkflowConditionAnd( array( $true, $true ) );
        $this->assertTrue( $condition->evaluate( true ) );
        $this->assertEquals( '( is true && is true )', (string)$condition );

        $condition = new ezcWorkflowConditionAnd( array( $true, $true ) );
        $this->assertFalse( $condition->evaluate( false ) );
    }

    /**
     * @expectedException ezcWorkflowDefinitionStorageException
     */
    public function testAnd2()
    {
        $condition = new ezcWorkflowConditionAnd( array( new StdClass ) );
    }

    public function testOr()
    {
        $true  = new ezcWorkflowConditionIsTrue;
        $false = new ezcWorkflowConditionIsFalse;

        $condition = new ezcWorkflowConditionOr( array( $true, $true ) );
        $this->assertTrue( $condition->evaluate( true ) );
        $this->assertFalse( $condition->evaluate( false ) );
        $this->assertEquals( '( is true || is true )', (string)$condition );

        $condition = new ezcWorkflowConditionOr( array( $true, $false ) );
        $this->assertTrue( $condition->evaluate( true ) );
        $this->assertTrue( $condition->evaluate( false ) );
    }

    public function testXor()
    {
        $true  = new ezcWorkflowConditionIsTrue;
        $false = new ezcWorkflowConditionIsFalse;

        $condition = new ezcWorkflowConditionXor( array( $true, $false ) );
        $this->assertTrue( $condition->evaluate( true ) );
        $this->assertTrue( $condition->evaluate( false ) );
        $this->assertEquals( '( is true XOR is false )', (string)$condition );

        $condition = new ezcWorkflowConditionXor( array( $true, $true ) );
        $this->assertFalse( $condition->evaluate( true ) );
        $this->assertFalse( $condition->evaluate( false ) );
    }
}
?>
