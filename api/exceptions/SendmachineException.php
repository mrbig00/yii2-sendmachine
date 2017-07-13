<?php
/**
 * @package yii2-sendmachine
 * @author  Zoltan Szanto <mrbig00@gmail.com>
 * @since   2017/07/13
 */

namespace mrbig00\sendmachine\api\exceptions;
/**
 * Class SendmachineException
 * @package mrbig00\sendmachine\Api
 */
class SendmachineException extends \Exception
{

    private $err_status;

    public function __construct($error_reason = "", $error_status = "")
    {
        parent::__construct($error_reason);
        $this->err_status = $error_status;
    }

    public function getSendmachineStatus()
    {
        return $this->err_status;
    }
}
