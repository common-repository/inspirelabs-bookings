<?php

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace BookingsVendor\Monolog\Handler;

use BookingsVendor\Monolog\Logger;
use BookingsVendor\Monolog\Formatter\JsonFormatter;
use BookingsVendor\PhpAmqpLib\Message\AMQPMessage;
use BookingsVendor\PhpAmqpLib\Channel\AMQPChannel;
use AMQPExchange;
class AmqpHandler extends \BookingsVendor\Monolog\Handler\AbstractProcessingHandler
{
    /**
     * @var AMQPExchange|AMQPChannel $exchange
     */
    protected $exchange;
    /**
     * @var string
     */
    protected $exchangeName;
    /**
     * @param AMQPExchange|AMQPChannel $exchange     AMQPExchange (php AMQP ext) or PHP AMQP lib channel, ready for use
     * @param string                   $exchangeName
     * @param int                      $level
     * @param bool                     $bubble       Whether the messages that are handled can bubble up the stack or not
     */
    public function __construct($exchange, $exchangeName = 'log', $level = \BookingsVendor\Monolog\Logger::DEBUG, $bubble = \true)
    {
        if ($exchange instanceof \AMQPExchange) {
            $exchange->setName($exchangeName);
        } elseif ($exchange instanceof \BookingsVendor\PhpAmqpLib\Channel\AMQPChannel) {
            $this->exchangeName = $exchangeName;
        } else {
            throw new \InvalidArgumentException('PhpAmqpLib\\Channel\\AMQPChannel or AMQPExchange instance required');
        }
        $this->exchange = $exchange;
        parent::__construct($level, $bubble);
    }
    /**
     * {@inheritDoc}
     */
    protected function write(array $record)
    {
        $data = $record["formatted"];
        $routingKey = $this->getRoutingKey($record);
        if ($this->exchange instanceof \AMQPExchange) {
            $this->exchange->publish($data, $routingKey, 0, array('delivery_mode' => 2, 'content_type' => 'application/json'));
        } else {
            $this->exchange->basic_publish($this->createAmqpMessage($data), $this->exchangeName, $routingKey);
        }
    }
    /**
     * {@inheritDoc}
     */
    public function handleBatch(array $records)
    {
        if ($this->exchange instanceof \AMQPExchange) {
            parent::handleBatch($records);
            return;
        }
        foreach ($records as $record) {
            if (!$this->isHandling($record)) {
                continue;
            }
            $record = $this->processRecord($record);
            $data = $this->getFormatter()->format($record);
            $this->exchange->batch_basic_publish($this->createAmqpMessage($data), $this->exchangeName, $this->getRoutingKey($record));
        }
        $this->exchange->publish_batch();
    }
    /**
     * Gets the routing key for the AMQP exchange
     *
     * @param  array  $record
     * @return string
     */
    protected function getRoutingKey(array $record)
    {
        $routingKey = \sprintf(
            '%s.%s',
            // TODO 2.0 remove substr call
            \substr($record['level_name'], 0, 4),
            $record['channel']
        );
        return \strtolower($routingKey);
    }
    /**
     * @param  string      $data
     * @return AMQPMessage
     */
    private function createAmqpMessage($data)
    {
        return new \BookingsVendor\PhpAmqpLib\Message\AMQPMessage((string) $data, array('delivery_mode' => 2, 'content_type' => 'application/json'));
    }
    /**
     * {@inheritDoc}
     */
    protected function getDefaultFormatter()
    {
        return new \BookingsVendor\Monolog\Formatter\JsonFormatter(\BookingsVendor\Monolog\Formatter\JsonFormatter::BATCH_MODE_JSON, \false);
    }
}
