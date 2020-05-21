<?php

namespace App\Service;

use App\Entity\Actionlog;
use Doctrine\ORM\EntityManagerInterface;

class ActionLoggerService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function addLog(string $content): void
    {
        $log = new Actionlog();
        $log->setContent($content);
        $log->setDatetime(new \DateTimeImmutable());

        $this->em->persist($log);
        $this->em->flush();
    }
}