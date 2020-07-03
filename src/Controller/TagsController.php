<?php

namespace App\Controller;

use App\Entity\Tags;
use App\Form\TagsType;
use App\Repository\TagsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/tags", name="tags.")
 */
class TagsController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(TagsRepository $tagsRepository)
    {  //Show all Tags
        $tags=$this->getDoctrine()->getRepository(Tags::class)->findAll();
        return $this->render('tags/index.html.twig', [
            'tags' => $tags,
        ]);
    }
    /**
     * @Route("/create",name="create")
     */
    public  function create(Request $request){
        //Create new Tag
        $tags=new Tags();
        $form= $this->createForm(TagsType::class,$tags);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($tags);
            $em->flush();
            return $this->redirectToRoute('tags.index');
        }

        return $this->render('tags/create.html.twig',[
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/edit/{id}",name="edit")
     */
    public function edit($id,Request $request)
    {  //Edit the tag according to the Id
        $tags=$this->getDoctrine()->getRepository(Tags::class)->find($id);
        $form=$this->createForm(TagsType::class,$tags);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($tags);
            $em->flush();
            return $this->redirectToRoute("tags.index");

        }
        return $this->render("tags/edit.html.twig",[
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/remove/{id}",name="remove")
     */
    //Remove the Tag according to the Id
    public function remove($id){
        $tags=$this->getDoctrine()->getRepository(Tags::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($tags);
        $em->flush();
        return $this->redirectToRoute('tags.index');
    }
}
