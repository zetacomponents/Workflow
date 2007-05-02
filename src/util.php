<?php
/**
 * File containing the ezcWorkflowUtil class.
 *
 * @package Workflow
 * @version //autogen//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Workflow utility methods.
 *
 * @package Workflow
 * @version //autogen//
 */
abstract class ezcWorkflowUtil
{
    /**
     * Finds an object in an array of objects.
     *
     * @param array $haystack The array of objects to search.
     * @param object $object The object to search for.
     * @return mixed The index position at which the object is located in the array, false otherwise.
     */
    public static function findObject( Array $haystack, $needle )
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
}
?>
