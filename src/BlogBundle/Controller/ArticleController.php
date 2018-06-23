<?php

namespace BlogBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Category;
use AppBundle\Entity\User;
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
     * @Route("/blog-list", name="blog_article_list")
     *
     */
    public function indexAction()
    {

        $categoryRepos = $this->getDoctrine()->getRepository(Category::class);
        $categories = $categoryRepos->findAll();

        $articleRepos = $this->getDoctrine()->getRepository(Article::class);
        $articles = $articleRepos->findAll();

        $userRepos = $this->getDoctrine()->getRepository(User::class);
        $users = $userRepos->findAll();

        return $this->render('@Blog/Article/article_list.html.twig', [
            'articles' => $articles,
            'categories' =>$categories,
            'users' =>$users,
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

        return $this->render('@Blog/Article/article_show.html.twig', [
            'article' => $article,

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
            ->getForm()
        ;
    }
}
