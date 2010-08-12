<?php
/**
 * File containing the ezcWorkflowExecutionListener interface.
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
 * @version //autogen//
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

/**
 * Interface for workflow execution listeners.
 *
 * @package Workflow
 * @version //autogen//
 */
interface ezcWorkflowExecutionListener
{
    /**
     * Debug severity constant.
     */
     const DEBUG          = 1;

    /**
     * Success audit severity constant.
     */
     const SUCCESS_AUDIT  = 2;

    /**
     * Failed audit severity constant.
     */
     const FAILED_AUDIT   = 4;

     /**
      * Info severity constant.
      */
     const INFO           = 8;

     /**
      * Notice severity constant.
      */
     const NOTICE         = 16;

     /**
      * Warning severity constant.
      */
     const WARNING        = 32;

     /**
      * Error severity constant.
      */
     const ERROR          = 64;

     /**
      * Fatal severity constant.
      */
     const FATAL          = 128;

    /**
     * Called to inform about events.
     *
     * @param string  $message
     * @param int $type
     */
    public function notify( $message, $type = ezcWorkflowExecutionListener::INFO );
}
?>
