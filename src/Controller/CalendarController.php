<?php

namespace App\Controller;

use App\Entity\Date;
use App\Entity\Blog;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    #[Route('/', name: 'app_calendar')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $personalDate = [];

        $globalDate = $doctrine->getRepository(Blog::class)->findAll();
        
        if ($this->getUser()) {
            $personalDate = $doctrine->getRepository(Date::class)->findByUser($this->getUser()->getId());
        }

        return $this->render('calendar/index.html.twig', [
            'globalDate' => $globalDate,
            'personalDate' => $personalDate
        ]);
    }
}
