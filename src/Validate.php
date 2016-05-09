<?php
namespace abraovic\iOSReceiptValidator;

use abraovic\iOSReceiptValidator\Exception\ConfigMissingException;
use abraovic\iOSReceiptValidator\Exception\iOSReceiptValidatorException;
use abraovic\iOSReceiptValidator\Transformers\Response;
use Symfony\Component\Yaml\Yaml;

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

class Validate
{
    /** @var array $config */
    private $config;

    /** @var Codec $codec*/
    private $codec;

    /**
     * If this is set to true the sandbox mode will be used
     *
     * @var bool
     */
    public static $dev = false;
    
    public static $receiptEncoded = false;

    function __construct($receipt, $sharedSecret = null)
    {
        $this->config = $this->loadConfiguration();
        $this->codec = new Codec($receipt, $sharedSecret);
    }

    public function execute()
    {
        $url = $this->config['ios']['url']['production'];
        if (self::$dev) {
            $url = $this->config['ios']['url']['development'];
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->codec->encodeRequest());

        $response = curl_exec($ch);
        $errno    = curl_errno($ch);
        $errmsg   = curl_error($ch);
        curl_close($ch);

        if ($errno != 0) {
            throw new iOSReceiptValidatorException($errmsg, $errno);
        }

        return $this->codec->decodeResponse($response);
    }

    /**
     * Loads configuration from config.yml
     *
     * @param $path -> if user would like to keep it somewhere else
     * @return array
     * @throws ConfigMissingException
     */
    private function loadConfiguration($path = "system")
    {
        if ($path == "system") {
            $path = realpath(dirname(__FILE__)) . "/../config.yml";
        }

        if (!is_file($path)) {
            throw new ConfigMissingException(
                "Configuration file [config.yml] is missing, or path: [" . $path . "] is invalid",
                404
            );
        }

        return Yaml::parse(file_get_contents($path));
    }
}