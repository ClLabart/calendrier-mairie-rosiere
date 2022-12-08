<?php

namespace App\Controller;

use App\Entity\Date;
use App\Entity\Blog;
use App\Form\DateCalendarType;
use App\Form\DateTimeCalendarType;
use App\Form\DateTimeEndCalendarType;
use App\Form\DateTimeStartCalendarType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    #[Route('/', name: 'app_calendar')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        $personalDate = [];

        $globalDate = $doctrine->getRepository(Blog::class)->findAll();
        
        if ($this->getUser()) {
            $personalDate = $doctrine->getRepository(Date::class)->findByUser($this->getUser()->getId());
        }

        // possible avec un !array_diff pour optimiser
        foreach ($personalDate as $index => $pDate) {
            if (in_array($pDate, $globalDate)) {
                unset($personalDate[$index]);
            }
        }

        $date = new Date();

        $valueStart = $request->request->all()['date_calendar']['dateStart'];
        $valueEnd = $request->request->all()['date_calendar']['dateEnd'];

        // Utiliser un formType différent pour bien enregistrer la date 😑
        // si on as rien dans le payload de la requête on prende celui de base
        if (str_contains($valueStart, 'T') && str_contains($valueEnd, 'T')) {
            $form = $this->createForm(DateTimeCalendarType::class, $date);
        } elseif ( str_contains($valueStart, 'T') && !str_contains($valueEnd, 'T')) {
            $form = $this->createForm(DateTimeStartCalendarType::class, $date);
        } elseif ( !str_contains($valueStart, 'T') && str_contains($valueEnd, 'T')) {
            $form = $this->createForm(DateTimeEndCalendarType::class, $date);
        } else {
            $form = $this->createForm(DateCalendarType::class, $date);
        }
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $date = $form->getData();
            $date->setUser($this->getUser());
            $doctrine->getManager()->persist($date);
            $doctrine->getManager()->flush($date);

            return $this->redirectToRoute('app_calendar');
        }

        return $this->render('calendar/index.html.twig', [
            'globalDate' => $globalDate,
            'personalDate' => $personalDate,
            'form' => $form->createView()
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
            $date->setBlog();
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
