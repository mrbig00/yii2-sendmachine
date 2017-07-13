<?php
/**
 * @package yii2-sendmachine
 * @author  Zoltan Szanto <mrbig00@gmail.com>
 * @since   2017/07/13
 */

namespace mrbig00\sendmachine;

use Yii;
use yii\base\Component;
use mrbig00\sendmachine\api\Client;

class Sendmachine extends Component
{
    public $username;
    public $password;
    public $debug;

    /**
     * @var $client Client
     */
    public $client;

    public function init()
    {
        parent::init();
        $this->client = new Client($this->username, $this->password);
    }

    public function welcome()
    {
        return "Hello..Welcome to MyComponent";
    }
}