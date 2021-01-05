<?php


namespace App\Controller;


use App\Entity\Timer;
use App\Repository\TimerRepository;
use App\Service\TimerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AppController extends AbstractController
{
    public function index(){

        return $this->render("index.html.twig");
    }

    public function getTimer(TimerService $timerService){

        $timer = $timerService->getLastTimer();
        // get the Timestamp bettwen
        $timestamp = $timerService->getTimestampDifference($timer);

        return $this->json(['timer' => $timestamp]);
    }

    public function restartTimer(TimerService $timerService, Request $request){


        $timer = $timerService->getLastTimer();
        // set the date when the timer end
        $timerService->endTimer($timer);

        // update best timer if nessecery
        $bestTimer = $timerService->updateBestTimer($timer);

        // Start a new timer
        $timerService->createTimer();
        $bestTimestamp = $timerService->getTimestampDifference($bestTimer);

        return $this->json(['timer' => $bestTimestamp]);
    }

    public function getBestTimer(Request $request){
        $em = $this->getDoctrine()->getManager();

        $now = new \DateTime('now');

        $target = new \DateTime('2020-12-27');
        $timer = $now->getTimestamp() - $target->getTimestamp();

        return $this->json(['timer' => $timer]);
    }
}