<?php
/**
 * @package yii2-sendmachine
 * @author  Zoltan Szanto <mrbig00@gmail.com>
 * @since   2017/07/13
 */

namespace mrbig00\sendmachine\api\lib;

use mrbig00\sendmachine\api\Client;

/**
 * Class Campaigns
 * @package mrbig00\sendmachine\api\lib
 */
class Campaigns
{
    public function __construct(Client $master)
    {
        $this->master = $master;
    }

    /**
     *get campaigns
     *
     * @param string  $filter  (campaign, transactional, archived, new, finalized, scheduled, sending, sent, cancelled)
     * @param string  $orderBy (name, cdate, total, opened, openedratio, clicked, clickedratio)
     * @param integer $offset
     * @param integer $limit
     * @param string  $search
     *
     * @return array
     * {
     *    "campaign": [
     *        {
     *            "campaign_id",
     *            "name",
     *            "cdate",
     *            "mdate",
     *            "autocreated",
     *            "state",
     *            "body_html",
     *            "schedule",
     *            "archived"
     *        },
     *        ...
     *    ],
     *    "total"
     * }
     */
    public function get($filter = 'all', $orderBy = 'cdate', $offset = 0, $limit = 25, $search = null)
    {
        $params = ['filter' => $filter, 'orderby' => $orderBy, 'offset' => $offset, 'limit' => $limit, 'search' => $search];

        return $this->master->request('/campaigns', 'GET', $params);
    }

    /**
     *
     * @param array $options
     *
     * @return array
     *
     */
    public function create($options = [])
    {
        return $this->master->request('/campaigns', 'POST', $options);
    }

    /**
     * Get campaign details (body is not sent here)
     *
     * @param int $campaign_id
     *
     * @return array
     * {
     *    "campaign": {
     *        "campaign_id",
     *        "name",
     *        "cdate",
     *        "mdate",
     *        "autocreated",
     *        "subject",
     *        "sender_name",
     *        "replyto",
     *        "schedule",
     *        "state",
     *        "archived",
     *        "contactlist_id",
     *        "segment_id",
     *        "ga_tracking",
     *        "personalize_to",
     *        "contactlist_name",
     *        "sender_email"
     *    }
     * }
     */
    public function details($campaign_id)
    {
        return $this->master->request('/campaigns/' . $campaign_id, 'GET');
    }

    /**
     * Update campaign
     *
     * @param int   $campaign_id
     * @param array $data
     *
     * @return array
     * {
     *    "status"
     * }
     */
    public function update($campaign_id, $data = [])
    {
        return $this->master->request('/campaigns/' . $campaign_id, 'POST', $data);
    }

    /**
     * Schedule campaign
     *
     * @param int    $campaign_id
     * @param string $date
     *
     * @return array
     * {
     *     "status"
     * }
     */
    public function schedule($campaign_id, $date = "")
    {
        $params = ['date' => $date];

        return $this->master->request('/campaigns/schedule/' . $campaign_id, 'POST', $params);
    }

    /**
     * Un-schedule campaign
     *
     * @param int $campaign_id
     *
     * @return array
     * {
     *     "status"
     * }
     */
    public function unschedule($campaign_id)
    {
        return $this->master->request('/campaigns/schedule/' . $campaign_id, 'DELETE');
    }

    /**
     * Send test email
     *
     * @param int    $campaign_id
     * @param string $addresses
     *
     * @return array
     * {
     *     "status"
     * }
     */
    public function test($campaign_id, $addresses = "")
    {
        $params = ['addresses' => $addresses];

        return $this->master->request('/campaigns/test/' . $campaign_id, 'POST', $params);
    }

    /**
     * send campaign
     *
     * @param int $campaign_id
     *
     * @return array
     * {
     *     "status"
     * }
     */
    public function send($campaign_id)
    {
        return $this->master->request('/campaigns/send/' . $campaign_id, 'POST');
    }

    /**
     * archive campaign
     *
     * @param int $campaign_id
     *
     * @return array
     * {
     *     "status"
     * }
     */
    public function archive($campaign_id)
    {
        return $this->master->request('/campaigns/archive/' . $campaign_id, 'POST');
    }

    /**
     * Un-archive campaign
     *
     * @param int $campaign_id
     *
     * @return array
     * {
     *     "status"
     * }
     */
    public function unarchive($campaign_id)
    {
        return $this->master->request('/campaigns/archive/' . $campaign_id, 'DELETE');
    }

    /**
     * check if campaign is ready for sending
     *
     * @param int $campaign_id
     *
     * @return array
     * {
     *     "status"
     * }
     */
    public function ready($campaign_id)
    {
        return $this->master->request('/campaigns/ready/' . $campaign_id, 'GET');
    }

    /**
     * Duplicate campaign
     *
     * @param int $campaign_id
     *
     * @return array
     * {
     *     "status",
     *     "new_id"
     * }
     */
    public function duplicate($campaign_id)
    {
        return $this->master->request('/campaigns/duplicate/' . $campaign_id, 'POST');
    }

    /**
     * Get campaign html and text content
     *
     * @param int $campaign_id
     *
     * @return array
     * {
     *    "source": {
     *        "body_text",
     *        "body_html"
     *    }
     * }
     */
    public function content($campaign_id)
    {
        return $this->master->request('/campaigns/content/' . $campaign_id, 'GET');
    }
}
