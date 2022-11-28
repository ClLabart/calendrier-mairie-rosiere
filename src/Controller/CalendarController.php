<?php

namespace App\Controller;

use App\Entity\Date;
use App\Entity\Blog;
use App\Form\DateCalendarType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/date/ajouter', name: 'app_date_add')]
    public function ajouterDate(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!($this->getUser())) {
            return $this->redirectToRoute('app_calendar');
        }

        $date = new Date();
        $form = $this->createForm(DateCalendarType::class, $date);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date->setUser($this->getUser());
            $entityManager->persist($date);
            $entityManager->flush();

            return $this->redirectToRoute('app_calendar');
        }

        return $this->render('calendar/add_date.html.twig', [
            'addDateForm' => $form->createView(),
        ]);
    }
}
