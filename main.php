<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use \Models\InterestQueue;
use \Models\ResponseModel;

/**
 * config file
 */
include('config.php');

/**
 * create connection with RabbitAMQP
 */
$connection = new AMQPStreamConnection(AMQP_HOST, AMQP_PORT, AMQP_USER, AMQP_PASS);
$channel = $connection->channel();

echo ' [*] Waiting for logs. To exit press CTRL+C', "\n";

$interestQueue = new InterestQueue();

/**
 * @param $msg
 *
 */
$callback = function ($msg) use ($channel, $interestQueue) {
    $responseModel = new ResponseModel($msg->body);

    if ($responseModel->getValid()) {

        /**
         * For each message it calculates the "interest" and total sum
         */
        $interest = $interestQueue->getInterests($responseModel->getSum(), $responseModel->getDays());
        $data = json_encode($interest);
        $msgReply = new AMQPMessage($data, array('delivery_mode' => 2, 'content_type' => 'application/json'));

        /**
         * Broadcasting the new message
         */
        $channel->basic_publish($msgReply, '', SOLVED_INTEREST_QUEUE);
        echo $data . "\n";
    }


};

/**
 * Listens on 'interest-queue' queue in default exchange for messages
 */
$channel->basic_consume(INTEREST_QUEUE, '', false, true, false, false, $callback);

/**
 * loop process
 */
while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();
