<?php
namespace abraovic\iOSReceiptValidator;
use abraovic\iOSReceiptValidator\Exception\iOSReceiptValidatorException;
use abraovic\iOSReceiptValidator\Transformers\Response;

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

class Codec
{
    /**
     * Response codes defined by apple
     * @see https://developer.apple.com/library/ios/releasenotes/General/ValidateAppStoreReceipt/Chapters/ValidateRemotely.html
     *
     * @var array
     */
    private $errorResponses = [
        0 => 'OK',
        21000 => 'The App Store could not read the JSON object you provided.',
        21002 => 'The data in the receipt-data property was malformed or missing.',
        21003 => 'The receipt could not be authenticated.',
        // Only returned for iOS 6 style transaction receipts for auto-renewable subscriptions.
        21004 => 'The shared secret you provided does not match the shared secret on file for your account.',
        21005 => 'The receipt server is not currently available.',
        // Only returned for iOS 6 style transaction receipts for auto-renewable subscriptions.
        21006 => 'This receipt is valid but the subscription has expired. When this status code is returned to your server, the receipt data is also decoded and returned as part of the response.',
        21007 => 'This receipt is from the test environment, but it was sent to the production environment for verification. Send it to the test environment instead.',
        21008 => 'This receipt is from the production environment, but it was sent to the test environment for verification. Send it to the production environment instead.'
    ];

    /**
     * Base64 encoded receipt
     *
     * @var string
     */
    private $encodedReceipt = '';

    /**
     * Only used for receipts that contain auto-renewable subscriptions.
     * Your app’s shared secret (a hexadecimal string).
     *
     * @var string
     */
    private $password = null;

    function __construct($receipt, $sharedSecret = null)
    {
        $this->encodedReceipt =
            $this->isJson($receipt) ? $receipt : Validate::$receiptEncoded ? $receipt : base64_encode($receipt);
        $this->password = $sharedSecret;
    }

    /**
     * Prepares JSON encoded string which will be used as a request body when
     * performing a POST Request to Apple server
     *
     * @return string (JSON encoded)
     */
    public function encodeRequest()
    {
        $request = [
            'receipt-data' => $this->encodedReceipt
        ];

        if ($this->password) {
            $request['password'] = $this->password;
        }

        return json_encode($request);
    }

    /**
     * Decodes response from Apple server and makes simple response object
     *
     * @param $response (JSON encoded)
     * @return object
     * @throws iOSReceiptValidatorException
     */
    public function decodeResponse($response)
    {
        // decode response into an object
        $response = json_decode($response);

        if ($response->status != 0) {
            throw new iOSReceiptValidatorException(
                "Apple server returned error with status: [" . 
                $response->status . " -> " . $this->errorResponses[$response->status] . 
                "]",
                500
            );
        }
        
        return new Response($response);
    }

    /**
     * Evaluates sting to check if it is JSON encoded
     *
     * @param $string
     * @return bool
     */
    private function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}