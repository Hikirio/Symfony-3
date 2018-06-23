<?php

namespace BlogBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Category;


use AppBundle\Entity\User;
use BlogBundle\Form\Search;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Article controller.
 *
 * @Route("blog")
 */
class ArticleController extends Controller
{
    /**
     * Lists all article entities.
     *
     * @Route("/list", name="blog_article_list")
     *
     */
    public function indexAction(Request $request)
    {
        $usersRepos = $this->getDoctrine()->getRepository(User::class);
        $users = $usersRepos->findAll();

        $categoryRepos = $this->getDoctrine()->getRepository(Category::class);
        $categories = $categoryRepos->findAll();

        $articleRepos = $this->getDoctrine()->getRepository(Article::class);
        $articles = $articleRepos->findAll();

        $form = $this->createForm(Search::class);
        $form->handleRequest($request);
        $art = null;

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $searchResult = $this->getDoctrine()->getRepository(Article::class);
            $art = $searchResult->getTitleByArticle($data['search']);


            return $this->render('@Blog/Article/article_list.html.twig', [
                'form' => $form->createView(),
                'articles' => $art,
                'categories' => $categories,
                'users'=> $users,
            ]);
        }

        return $this->render('@Blog/Article/article_list.html.twig', [
            'form' => $form->createView(),
            'articles' => $articles,
            'categories' => $categories,
            'users'=> $users,
        ]);
    }


    /**
     * Creates a new article entity.
     *
     * @Route("/new", name="blog_article_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm('BlogBundle\Form\ArticleType', $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('blog_article_list', array('id' => $article->getId()));
        }

        return $this->render('@Blog/Article/article_add.html.twig', array(
            'article' => $article,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a article entity.
     *
     * @Route("/{id}", name="blog_article_show")
     * @Method("GET")
     */
    public function showAction(Article $article)
    {
        $usersRepos = $this->getDoctrine()->getRepository(User::class);
        $users = $usersRepos->findAll();


        return $this->render('@Blog/Article/article_show.html.twig', [
            'article' => $article,
            'users'=> $users,

        ]);
    }

    /**
     * Displays a form to edit an existing article entity.
     *
     * @Route("/{id}/edit", name="blog_article_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Article $article)
    {
        $deleteForm = $this->createDeleteForm($article);
        $editForm = $this->createForm('BlogBundle\Form\ArticleType', $article);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('blog_article_show', ['id' => $article->getId()]);
        }

        return $this->render('@Blog/Article/article_edit.html.twig', [
            'article' => $article,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a article entity.
     *
     * @Route("/{id}/", name="blog_article_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Article $article)
    {
        $form = $this->createDeleteForm($article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();
        }
        return $this->redirectToRoute('blog_article_list');
    }

    /**
     * Creates a form to delete a article entity.
     *
     * @param Article $article The article entity
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createDeleteForm(Article $article)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('blog_article_delete', ['id' => $article->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Creates a new article entity.
     *
     * @Route("/search", name="search")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function searchAction(Request $request)
    {
        $form = $this->createForm(SearchArticle::class);
        $form->handleRequest($request);
        $articles = null;
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $searchResult = $this->getDoctrine()->getRepository(Article::class);
            $articles = $searchResult->getTitleByArticle($data['search']);


        }
        return $this->render('article/SearchArticle.html.twig', array(
            'form' => $form->createView(),
            'articles' => $articles
        ));
    }
}
