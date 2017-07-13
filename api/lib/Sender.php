<?php
/**
 * @package yii2-sendmachine
 * @author  Zoltan Szanto <mrbig00@gmail.com>
 * @since   2017/07/13
 */

namespace mrbig00\sendmachine\api\lib;

use mrbig00\sendmachine\api\Client;

/**
 * Class Sender
 * @package mrbig00\sendmachine\api\lib
 */
class Sender
{
    public function __construct(Client $master)
    {
        $this->master = $master;
    }

    /**
     * get sender list
     *
     * @param string $status (active, pending, active+pending, all)
     * @param string $type   (email, domain, all)
     * @param string $group  (none, domain, flat)
     * @param int    $limit
     * @param int    $offset
     *
     * @return array
     * {
     *    "senderlist": [
     *        {
     *            "email",
     *            "type",
     *            "emailtype",
     *            "status",
     *            "label"
     *        },
     *        ...
     *    ],
     *    "total"
     * }
     */
    public function get($status = 'active', $type = 'email', $group = null, $limit = null, $offset = null)
    {
        $params = ['status' => $status, 'type' => $type, 'group' => $group, 'limit' => $limit, 'offset' => $offset];

        return $this->master->request('/sender', 'GET', $params);
    }

    /**
     * add a new sender
     *
     * @param string $email
     *
     * @return array
     * {
     *    "sender": {
     *        "address",
     *        "type"
     *    },
     *    "status"
     * }
     */
    public function add($email)
    {
        $params = ['type' => 'email', 'address' => $email];

        return $this->master->request('/sender', 'POST', $params);
    }

    /**
     * delete sender by email address
     *
     * @param string $sender_email
     *
     * @return array
     * {
     *    "status"
     * }
     */
    public function delete($sender_email)
    {

        return $this->master->request('/sender/' . $sender_email, 'DELETE');
    }
}
