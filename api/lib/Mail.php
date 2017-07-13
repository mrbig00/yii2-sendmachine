<?php
/**
 * @package yii2-sendmachine
 * @author  Zoltan Szanto <mrbig00@gmail.com>
 * @since   2017/07/13
 */

namespace mrbig00\sendmachine\api\lib;

use mrbig00\sendmachine\api\Client;

/**
 * Class Mail
 * @package mrbig00\sendmachine\api\lib
 */
class Mail
{
    public function __construct(Client $master)
    {
        $this->master = $master;
    }

    /**
     * Send mail
     *
     * @param array $details
     *
     * @return array
     * {
     *    "sent"
     *    "status"
     * }
     */
    public function send($details)
    {
        return $this->master->request('/mail/send', 'POST', $details);
    }
}
