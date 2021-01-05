<?php


namespace App\Service;


use App\Entity\Timer;
use App\Repository\TimerRepository;
use Doctrine\ORM\EntityManagerInterface;

class TimerService
{
    private $timerRepository;
    private $em;

    public function __construct(TimerRepository $timerRepository, EntityManagerInterface $em)
    {
        $this->timerRepository = $timerRepository;
        $this->em = $em;
    }

    public function createTimer(): Timer{
        $timer = new Timer();
        $this->em->persist($timer);
        $this->em->flush();

        return $timer;
    }

    public function updateBestTimer(Timer $timer): Timer
    {
        $bestTimer = $this->timerRepository->findOneBy(['isBest' => true]);

        $timestamp = $this->getTimestampDifference($timer);
        $bestTimestamp = $this->getTimestampDifference($bestTimer);

        if ($bestTimestamp < $timestamp){
            $bestTimer->setIsBest(false);
            $timer->setIsBest(true);

            $this->em->persist($bestTimer);
            $this->em->persist($timer);
            $this->em->flush();

            return $timer;
        }

        return $bestTimer;
    }

    public function getTimestampDifference(Timer $timer):int {
        if (!empty($timer->getEndAt())){
            return $timer->getEndAt()->getTimestamp() - $timer->getCreateAt()->getTimestamp();
        }

        $now = new \DateTime();
        return $now->getTimestamp() - $timer->getCreateAt()->getTimestamp();
    }

    public function getLastTimer(): Timer
    {
        $timer = $this->timerRepository->findOneBy([], ['createAt'=> 'DESC']);

        if(!$timer){
            $timer = new Timer();
            $timer->setCreateAt(new \DateTime('2020-12-27'));
            $timer->setIsBest(true);

            $this->em->persist($timer);
            $this->em->flush();
        }

        return $timer;
    }

    public function endTimer(Timer $timer): Timer
    {
        $timer->setEndAt(new \DateTime());

        $this->em->persist($timer);
        $this->em->flush();

         return $timer;
    }


}