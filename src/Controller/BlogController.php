<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\CategorySearch;
use App\Form\CategorySearchType;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends AbstractController
{
    #[Route('/', name: 'blog')]
    public function index(): Response
    {
        return $this->redirectToRoute('article_index');
    }

    /**
     * @Route("/art_cat/", name="article_par_cat")
     * Method({"GET", "POST"})
     */
    public function articlesParCategorie(Request $request) {
        $categorySearch = new CategorySearch();
        $form = $this->createForm(CategorySearchType::class,$categorySearch);
        $form->handleRequest($request);

        $articles=[];

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $categorySearch->getCategory();
            if ($category!="")
                $articles=$category->getArticles();
            else
                $articles=$this->getDoctrine()->getRepository(Article::class)->findAll();
        }

        if (isset($category)) {
            return $this->render('article/articlesParCategorie.html.twig', ['form' => $form->createView(),'articles' => $articles, 'categorie' => $category->getName()]);
        }
        else {
            return $this->render('article/articlesParCategorie.html.twig', ['form' => $form->createView(),'articles' => $articles]);
        }
    }
}
