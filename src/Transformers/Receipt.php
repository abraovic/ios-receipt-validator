<?php
namespace abraovic\iOSReceiptValidator\Transformers;

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
 */

class Receipt
{
    /** @var string */
    private $bundleId;
    /** @var string */
    private $applicationVersion;
    private $inApp;
    /**
     * In the sandbox environment, the value of this field is always “1.0".
     *
     * @var string
     */
    private $originalApplicationVersion;
    private $creationDate;
    private $expirationDate;

    /**
     * @param \stdClass $receipt
     */
    function __construct($receipt)
    {
        $this->bundleId = $receipt->bundle_id;
        $this->applicationVersion = $receipt->application_version;
        $this->inApp = $receipt->in_app;
        $this->originalApplicationVersion = $receipt->original_application_version;
        $this->creationDate = $receipt->creation_date;
        $this->expirationDate = $receipt->expiration_date;
    }

    /**
     * @return string
     */
    public function getBundleId()
    {
        return $this->bundleId;
    }

    /**
     * @return string
     */
    public function getApplicationVersion()
    {
        return $this->applicationVersion;
    }

    /**
     * @return mixed
     */
    public function getInApp()
    {
        return $this->inApp;
    }

    /**
     * @return string
     */
    public function getOriginalApplicationVersion()
    {
        return $this->originalApplicationVersion;
    }

    /**
     * @return mixed
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @return mixed
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }
}