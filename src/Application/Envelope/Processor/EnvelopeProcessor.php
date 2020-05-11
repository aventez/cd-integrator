<?php

namespace App\Application\Envelope\Processor;

use App\Application\Envelope\Handler\EnvelopeHandlerInterface;
use Enqueue\Consumption\QueueSubscriberInterface;
use Interop\Queue\Context;
use Interop\Queue\Message;
use Interop\Queue\Processor;
use Psr\Log\LoggerInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;

abstract class EnvelopeProcessor implements Processor, QueueSubscriberInterface
{
    private $logger;
    private $envelopeHandler;

    public function __construct
    (
        LoggerInterface $logger,
        EnvelopeHandlerInterface $envelopeHandler
    )
    {
        $this->logger = $logger;
        $this->envelopeHandler = $envelopeHandler;
    }

    public function process(Message $message, Context $context)
    {
        try {
            $envelope = unserialize($message->getBody());
            if( !$this->envelopeHandler->supports($envelope) ) {
                throw new \Exception(sprintf('Handler %s does not support envelope %s', get_class($this->envelopeHandler), get_class($envelope)));
            }

            $handlerResponse = $this->envelopeHandler->handle($envelope);

            return in_array($handlerResponse, [self::REQUEUE, self::ACK, self::REJECT]) ? $handlerResponse : self::REJECT;
        } catch(\Throwable $ex) {
            $this->logger->error(
                sprintf('%s occured (%s)', get_class($ex), $ex->getMessage()),
                [
                    'message' => $message->getBody(),
                    'file' => $ex->getFile(),
                    'line' => $ex->getLine(),
                    'status' => $this->parseExceptionToConsumeResult($ex)
                ]
            );

            return $this->parseExceptionToConsumeResult($ex);
        }
    }

    private function parseExceptionToConsumeResult(\Throwable $ex)
    {
        if( $ex instanceof ConnectException ) {
            return self::REQUEUE;
        }

        if( $ex instanceof ServerException && in_array($ex->getResponse()->getStatusCode(), [504, 503]) ) {
            return self::REQUEUE;
        }

        return self::ACK;
    }
}