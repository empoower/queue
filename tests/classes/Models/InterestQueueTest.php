<?php
use \Models\InterestQueue;
use \Models\ResponseModel;

class InterestQueueTest extends PHPUnit_Framework_TestCase
{

    private $interestQueue;


    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        /**
         * It is very important to create an object of the InterestQueue class in the constructor,
         * thus generated main array for further calculations
         */
        $this->interestQueue = new InterestQueue();
    }

    /**
     * @dataProvider providerInterest
     */
    public function  testInterest($dayNum, $truthRes)
    {
        $interest = $this->interestQueue->calcInterest($dayNum);
        $this->assertEquals($truthRes, $interest);
    }

    /**
     * @return array
     *
     * If day is...
     *  divisible by three, the interest is: 1%
     *  divisible by five, the interest is: 2%
     *  divisible by both three and five, the interest is: 3%
     *  not divisible by either three or five, interest is: 4%
     *
     */
    public function  providerInterest()
    {
        return array(
            array(1, 4),
            array(2, 4),
            array(3, 1),
            array(4, 4),
            array(5, 2),
            array(6, 1),
            array(7, 4),
            array(8, 4),
            array(9, 1),
            array(10, 2),
            array(11, 4),
            array(12, 1),
            array(13, 4),
            array(14, 4),
            array(15, 3),
            array(16, 4),
            array(17, 4),
            array(18, 1),
            array(19, 4),
            array(20, 2),
            array(21, 1),
            array(22, 4),
            array(23, 4),
            array(24, 1),
            array(25, 2),
            array(26, 4),
            array(27, 1),
            array(28, 4),
            array(29, 4),
            array(30, 3),


        );
    }

    /**
     * @dataProvider providerOutgoingMessages
     */
    public function  testOutgoingMessages($data, $truthRes)
    {
        $interest = $this->interestQueue->getInterests($data['sum'], $data['days']);
        $this->assertEquals($truthRes, $interest);
    }

    /**
     * @return array
     *
     * EXAMPLE:
     * input:  ["sum" => 123, "days" => 5]
     * output: ["sum" => 123, "days" => 5, "interest" => 18.45, "totalSum" => 141.45, "token" => TOKEN]
     *
     */
    public function  providerOutgoingMessages()
    {
        return array(
            [["sum" => 123, "days" => 5], ["sum" => 123, "days" => 5, "interest" => 18.45, "totalSum" => 141.45, "token" => TOKEN]],
            [["sum" => 667, "days" => 18], ["sum" => 667, "days" => 18, "interest" => 346.84, "totalSum" => 1013.84, "token" => TOKEN]],
            [["sum" => 989, "days" => 280419844], ["sum" => 989, "days" => 280419844, "interest" => 7950276485.69, "totalSum" => 7950277474.69, "token" => TOKEN]],
            [["sum" => 833.48, "days" => 1], ["sum" => 833.48, "days" => 1, "interest" => 33.34, "totalSum" => 866.82, "token" => TOKEN]],
        );
    }

    /**
     * @dataProvider providerBadResponse
     */
    public function  testBadResponse($json, $truthRes)
    {
        $responseModel = new ResponseModel($json);
        $this->assertEquals($truthRes, $responseModel->getValid());
    }

    /**
     * @return array
     *
     * sum: natural number greater than zero
     * days: (natural number or floating point number) greater than zero
     */
    public function  providerBadResponse()
    {
        return array(
            ['{"sum":"" ,"days": "" }', null],
            ['{"sum": "","days": 1 }', null],
            ['{"sum": 1,"days": "" }', null],
            ['{"sum": 0,"days": 0 }', null],
            ['{"sum": 0,"days": 1 }', null],
            ['{"sum": 1,"days": 0 }', null],
            ['{"sum": "abc","days": 1 }', null],
            ['{"sum": 1,"days": "abc" }', null],
            ['{"sum": -1,"days": -1 }', null],
            ['{"sum": 1,"days": -1 }', null],
            ['{"sum": -1,"days": 1 }', null],
            ['{"sum": -1,"days": 1 }', null],
            ['{"sum": 123,"days": 123.123 }', null],
            ['{"sum": 123.222.222,"days": 1 }', null],
        );
    }

}
