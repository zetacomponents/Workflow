<?php
/**
 * File containing the ezcWorkflowUtil class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Workflow utility methods.
 *
 * @package Workflow
 * @version //autogen//
 * @ignore
 */
abstract class ezcWorkflowUtil
{
    /**
     * Finds the object $needle in an array of objects
     * and returns the position in the $haystack
     *
     * @param array $haystack The array of objects to search.
     * @param object $needle The object to search for.
     * @return mixed The index position at which the object is located in the array, false otherwise.
     */
    public static function findObject( array $haystack, $needle )
    {
        $keys  = array_keys( $haystack );
        $count = count( $keys );

        for ( $i = 0; $i < $count; $i++ )
        {
            if ( $haystack[$keys[$i]] === $needle )
            {
                return $keys[$i];
            }
        }

        return false;
    }

    /**
     * Returns the default configuration for a node class.
     *
     * @param string $className
     * @return mixed
     */
    public static function getDefaultConfiguration( $className )
    {
        $configuration = null;

        $class    = new ReflectionClass( $className );
        $defaults = $class->getDefaultProperties();

        if ( isset( $defaults['configuration'] ) )
        {
            $configuration = $defaults['configuration'];
        }

        return $configuration;
    }

    /**
     * Returns a compact textual representation of a PHP variable.
     *
     * @param mixed $variable
     * @return string
     */
    public static function variableToString( $variable )
    {
        if ( $variable === null )
        {
            return '<null>';
        }

        if ( $variable === true )
        {
            return '<true>';
        }

        if ( $variable === false )
        {
            return '<false>';
        }

        if ( is_array( $variable ) )
        {
            return '<array>';
        }

        if ( is_object( $variable ) )
        {
            return '<' . get_class( $variable ) . '>';
        }

        return $variable;
    }
}
?>
