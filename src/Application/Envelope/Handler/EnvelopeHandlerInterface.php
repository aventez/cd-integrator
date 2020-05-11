<?php

namespace App\Application\Envelope\Handler;

use App\Application\Envelope\EnvelopeInterface;

interface EnvelopeHandlerInterface
{
    const ACK = 'enqueue.ack';
    const REJECT = 'enqueue.reject';
    const REQUEUE = 'enqueue.requeue';

    public function handle(EnvelopeInterface $envelope): string;
    public function supports(EnvelopeInterface $envelope): bool;
}