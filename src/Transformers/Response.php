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

class Response
{
    /**
     * Either 0 if the receipt is valid, or one of the error codes in Codec.
     * For iOS 7 style app receipts, the status code is reflects the status of the app receipt as a whole.
     * For example, if you send a valid app receipt that contains an expired subscription, the response is
     * 0 because the receipt as a whole is valid.
     *
     * @var int
     */
    private $status;
    /**
     * An object containing the receipt that was sent for verification
     * 
     * @var Receipt
     */
    private $receipt;
    /**
     * Only returned for iOS 6 style transaction receipts for auto-renewable subscriptions.
     * The base-64 encoded transaction receipt for the most recent renewal
     *
     * @var string
     */
    private $latestReceipt = null;
    /**
     * Only returned for iOS 6 style transaction receipts for auto-renewable subscriptions.
     * The JSON representation of the receipt for the most recent renewal.
     *
     * @var Receipt
     */
    private $latestReceiptInfo = null;

    /**
     * @param \stdClass
     */
    function __construct($response)
    {
        $this->status = $response->status;
        $this->receipt = new Receipt($response->receipt);

        if (isset($response->latest_receipt)) {
            $this->latestReceipt = $response->latest_receipt;
        }
        if (isset($response->latest_receipt_info)) {
            $this->latestReceiptInfo = new Receipt($response->latest_receipt_info);
        }
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return Receipt
     */
    public function getReceipt()
    {
        return $this->receipt;
    }

    /**
     * @return string
     */
    public function getLatestReceipt()
    {
        return $this->latestReceipt;
    }

    /**
     * @return Receipt
     */
    public function getLatestReceiptInfo()
    {
        return $this->latestReceiptInfo;
    }
}