<?php
/**
 * @package yii2-sendmachine
 * @author  Zoltan Szanto <mrbig00@gmail.com>
 * @since   2017/07/13
 */

namespace mrbig00\sendmachine\api;

use mrbig00\sendmachine\api\exceptions\SendmachineException;
use mrbig00\sendmachine\api\exceptions\SendmachineHttpException;
use mrbig00\sendmachine\api\lib\Account;
use mrbig00\sendmachine\api\lib\Campaigns;
use mrbig00\sendmachine\api\lib\Lists;
use mrbig00\sendmachine\api\lib\Mail;
use mrbig00\sendmachine\api\lib\Sender;
use mrbig00\sendmachine\api\lib\Templates;

/**
 * Class Client
 * @package mrbig00\sendmachine\api
 */
class Client
{
    /**
     * api host
     * @var string
     */
    private $api_host = 'https://api.sendmachine.com';
    /**
     * api username
     * @var string
     */
    private $username;
    /**
     * api password
     * @var string
     */
    private $password;
    /**
     * Curl resource
     * @var resource
     */
    private $curl;
    /*
     * for debugging
     */
    private $debug = false;

    /**
     * Client constructor.
     *
     * @param null $username
     * @param null $password
     *
     * @throws SendmachineException
     */
    public function __construct($username = null, $password = null)
    {
        if (!$username || !$password) {
            list($username, $password) = $this->checkConfig();
        }
        if (!$username || !$password) {
            throw new SendmachineException("You must provide a username and password", "no_username_password");
        }
        $this->username = $username;
        $this->password = $password;
        $this->curl = curl_init();
        $this->campaigns = new Campaigns($this);
        $this->sender = new Sender($this);
        $this->lists = new Lists($this);
        $this->account = new Account($this);
        $this->templates = new Templates($this);
        $this->mail = new Mail($this);
    }

    public function request($url, $method, $params = [])
    {
        $ch = $this->curl;

        switch (strtoupper($method)) {
            case 'GET':
                if (count($params)) {
                    $url .= "?" . http_build_query($params);
                }
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                break;
            case 'PUT':
            case 'POST':
                $params = json_encode($params);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Content-Length: ' . strlen($params)]);
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                break;
        }

        $final_url = $this->api_host . $url;

        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_DEFAULT);
        curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $final_url);
        curl_setopt($ch, CURLOPT_VERBOSE, $this->debug);
        if ($this->debug) {
            $start = microtime(true);
            $this->log('URL: ' . $this->api_host . $url . (is_string($params) ? ", params: " . $params : ""));
        }
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        if ($this->debug) {
            $time = microtime(true) - $start;
            $this->log('Completed in ' . number_format($time * 1000, 2) . 'ms');
            $this->log('Response: ' . $response);
        }
        if (curl_error($ch)) {
            throw new SendmachineHttpException("API call to $this->api_host$url failed.Reason: " . curl_error($ch));
        }
        $result = json_decode($response, true);
        if ($response && !$result)
            $result = $response;
        if ($info['http_code'] >= 400) {
            $this->setError($result);
        }

        return $result;
    }

    public function __destruct()
    {
        if (is_resource($this->curl)) {
            curl_close($this->curl);
        }
    }

    public function log($msg)
    {
        error_log($msg);
    }

    public function checkConfig()
    {
        $config_paths = [".sendmachine.conf", "/etc/.sendmachine.conf"];
        $username = null;
        $password = null;
        foreach ($config_paths as $path) {
            if (file_exists($path)) {
                if (!is_readable($path)) {
                    throw new SendmachineException("Configuration file ($path) does not have read access.", "config_not_readable");
                }
                $config = parse_ini_file($path);
                $username = empty($config['username']) ? null : $config['username'];
                $password = empty($config['password']) ? null : $config['password'];
                break;
            }
        }

        return [$username, $password];
    }

    public function setError($result)
    {
        if (is_array($result)) {
            if (empty($result['error_reason'])) {
                if (!empty($result['status']))
                    $result['error_reason'] = $result['status'];
                else
                    $result['error_reason'] = "Unexpected error";
            }
            throw new SendmachineException($result['error_reason'], $result['status']);
        }
    }
}
