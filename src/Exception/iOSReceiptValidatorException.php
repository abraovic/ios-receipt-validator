<?php
namespace abraovic\iOSReceiptValidator\Exception;

/**
 *     Copyright 2016
 *
 *     Licensed under the Apache License, Version 2.0 (the "License");
 *     you may not use this file except in compliance with the License.
 *     You may obtain a copy of the License at
 *
 *         http://www.apache.org/licenses/LICENSE-2.0
 *
 *     @author Ante Braović - abraovic@gmail.com - antebraovic.me
 *
 *     Custom iOSReceiptValidator exception handler
 */

class iOSReceiptValidatorException extends \Exception
{
    public function __construct($message, $code, \Exception $previous = null)
    {
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }
}