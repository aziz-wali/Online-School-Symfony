<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/categories", name="categories.")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
         //get all Categories from the Entity Category
        $cats =$this->getDoctrine()->getRepository('App\Entity\Category')->findAll();
        return $this->render('category/index.html.twig', [
            'cats' => $cats
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {
        // create new category

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('categories.index');
        }


        return $this->render('category/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
        /**
         * @Route("/edit/{id}",name="edit")
         */
        public function edit(Request $request, $id)
         {
             // edit the category according to id
          $category= $this->getDoctrine()->getRepository(Category::class)->find($id);
          $form=$this->createForm(CategoryType::class,$category);
          $form->handleRequest($request);
          if($form->isSubmitted()){
              $em=$this->getDoctrine()->getManager();
              $em->persist($category);
              $em->flush();
              return $this->redirect($this->generateUrl('categories.index'));
          }
          return $this->render('category/edit.html.twig',
          ['form'=>$form->createView()]

          );

        }

        /**
         * @Route("/remove/{id}",name="remove")
         */

       public function remove($id)
    {
        //remove the category from DB
        $category=$this->getDoctrine()->getRepository(Category::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute("categories.index");


    }

}
