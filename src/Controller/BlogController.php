<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\AddBlogType;
use App\Repository\DateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $blogs = $doctrine->getRepository(Blog::class)->findAll();

        return $this->render('blog/index.html.twig', [
            'blogs' => $blogs,
        ]);
    }

    #[Route('/blog/article/{id}', name: 'app_blog_article')]
    public function article(int $id, ManagerRegistry $doctrine): Response
    {
        $blog = $doctrine->getRepository(Blog::class)->find($id);

        return $this->render('blog/article.html.twig', [
            'blog' => $blog,
        ]);
    }

    #[Route('/blog/ajouter', name: 'app_blog_add')]
    public function ajouterBlog(Request $request, EntityManagerInterface $entityManager, DateRepository $dateRepository): Response
    {
        if (!($this->getUser())) {
            return $this->redirectToRoute('app_blog');
        }

        $blog = new Blog();
        $form = $this->createForm(AddBlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute('app_blog'); //mettre id en parametre
        }

        $dateNull = $dateRepository->trieDateQueryBuilder();

        return $this->render('blog/add_blog.html.twig', [
            'addBlogForm' => $form->createView(),
            'dateNull' => $dateNull,
        ]);
    }
}
