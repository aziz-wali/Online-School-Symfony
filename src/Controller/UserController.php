<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/user", name="user.")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $q=" SELECT * FROM user  WHERE status=0  Order BY id DESC ";
        $sql= $this->getDoctrine()->getManager()->getConnection()->prepare($q);
        $sql->execute();
        $users=$sql->fetchAll();
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit($id,Request $request)
    {
        $user= $this->getDoctrine()->getRepository(User::class)->find($id);
        $form= $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em=$this->getDoctrine()->getManager();
            $file=$request->files->get('user')['avatar'];


            if($file){
                $filename=md5(uniqid()).'.' .$file->guessClientExtension();
                $file->move(
                    $this->getParameter('uploads_dir'),
                    $filename
                );

                $user->setAvatar($filename);

                $em->persist($user);
                $em->flush();
                return $this->redirectToRoute('user.index');
            }
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/remove/{id}",name="remove")
     */
    public  function remove($id)
    {
        $user=$this->getDoctrine()->getRepository(User::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('user.index');


    }
    /**
     * @Route("/lehrer",name="lehrer")
     */
    public function lehrer(UserRepository $userRepository)
    {
        $users=$this->getDoctrine()->getRepository(User::class)
            ->findBy(['status'=>1],['id'=>'DESC']);



        return $this->render('user/lehrer.html.twig',[
            'users'=>$users,
        ]);
    }
}
