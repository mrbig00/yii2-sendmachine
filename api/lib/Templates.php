<?php
/**
 * @package yii2-sendmachine
 * @author  Zoltan Szanto <mrbig00@gmail.com>
 * @since   2017/07/13
 */

namespace mrbig00\sendmachine\api\lib;

use mrbig00\sendmachine\api\Client;

/**
 * Class Templates
 * @package mrbig00\sendmachine\api\lib
 */
class Templates
{
    public function __construct(Client $master)
    {
        $this->master = $master;
    }

    /**
     * get templates
     *
     * @param int $limit
     * @param int $offset
     *
     * @return array
     * {
     *    "list": [
     *        {
     *            "tpl_id",
     *            "name",
     *            "date",
     *            "mdate"
     *        },
     *        ...
     *    ],
     *    "total"
     * }
     */
    public function get($limit = 25, $offset = 0)
    {
        $params = ['limit' => $limit, 'offset' => $offset];

        return $this->master->request('/templates', 'GET', $params);
    }

    /**
     * Get a single template
     *
     * @param int $template_id
     *
     * @return array
     * {
     *    "body",
     *    "id",
     *    "name"
     * }
     */
    public function details($template_id)
    {
        return $this->master->request('/templates/' . $template_id, 'GET');
    }

    /**
     * Create a new template
     *
     * @param string $name
     * @param string $body
     *
     * @return array
     * {
     *    "status"
     * }
     */
    public function create($name, $body = "")
    {
        $params = ['name' => $name, 'body' => $body];

        return $this->master->request('/templates', 'POST', $params);
    }

    /**
     * edit template body
     *
     * @param int    $template_id
     * @param string $body
     *
     * @return array
     * {
     *    "status"
     * }
     */
    public function update($template_id, $body = "")
    {
        $params = ['body' => $body];

        return $this->master->request('/templates/' . $template_id, 'POST', $params);
    }

    /**
     * Delete a template
     *
     * @param int $template_id
     *
     * @return array
     * {
     *    "status"
     * }
     */
    public function delete($template_id)
    {
        return $this->master->request('/templates/' . $template_id, 'DELETE');
    }
}
