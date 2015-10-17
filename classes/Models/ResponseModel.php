<?php
/**
 * Created by PhpStorm.
 * User: ййй
 * Date: 15.10.2015
 * Time: 11:59
 */
namespace Models;
class ResponseModel
{
    private $sum;
    private $days;
    private $valid;

    /**
     * @param $msgBody
     */
    public function __construct($msgBody)
    {
        $data = json_decode($msgBody, true);

        if ($data !== null) {
            $this->validateData($data);
        }


    }

    /**
     * @param $data
     */
    public function validateData($data)
    {
        if (array_key_exists('sum', $data) && array_key_exists('days', $data)) {
            $sum = $data['sum'];
            $days = $data['days'];
            if (is_int($days) && is_numeric($sum) && $days>0 && $sum>0) {
                $this->days = $days;
                $this->sum = $sum;
                $this->valid = true;
            }
        }
    }

    /**
     * @param $days
     */
    public function setDays($days)
    {
        $this->days = $days;
    }

    /**
     * @param $sum
     */
    public function setSum($sum)
    {
        $this->sum = $sum;
    }

    /**
     * @return mixed
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * @return mixed
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * @return mixed
     */
    public function getValid()
    {
        return $this->valid;
    }
}