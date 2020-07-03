<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/post", name="post.")
 */
class PostController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index(PostRepository $postRepository)
    {
        //this function used to display all posts from DB in the Template

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $videos=$postRepository->findAll();

        return $this->render('post/index.html.twig', [
            'videos' =>$videos
        ]);
    }


    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {
  //Create new video or post
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $post= new Post();
        $form=$this->createForm(PostType::class,$post);

        $form->handleRequest($request);

        if($form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();

            $file=$request->files->get('post')['image'];
            if($file){
                $filename=md5(uniqid()).'.' .$file->guessClientExtension();

                $file->move(
                    $this->getParameter('uploads_dir'),
                    $filename
                );

                $post->setImage($filename);
                $em->persist($post);
                $em->flush();
            }




            return $this->redirect($this->generateUrl('post.index'));
        }
        return $this->render('post/create.html.twig',['form'=> $form->createView()]);
    }
//-------------------------------------


    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit($id,Request $request)
    {
        //Edit post or Video according to the Id
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);
        $form=$this->createForm(PostType::class,$post);
        $form->handleRequest($request);

        if($form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();

            $file=$request->files->get('post')['image'];
            if($file){
                $filename=md5(uniqid()).'.' .$file->guessClientExtension();
                $file->move(
                    $this->getParameter('uploads_dir'),
                    $filename
                );
                $post->setImage($filename);
                $em->persist($post);
                $em->flush();
            }




            return $this->redirect($this->generateUrl('post.index'));
        }
        return $this->render('post/edit.html.twig',['form'=> $form->createView()]);
    }
    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove(Post $post)
    {  //remove post or Video according to the Id

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $em = $this->getDoctrine()->getManager();

        $em->remove($post);
        $em->flush();
        return $this->redirect($this->generateUrl('post.index'));
    }
    /**
     * @Route("/show/{id}", name="show")
     */

    //Show the  post or Video according to the Id
    public function show($id,PostRepository $postRepository){
        $video=$postRepository->find($id);
        return $this->render('post/show.html.twig',[
            'video'=>$video
        ]);
    }

}
