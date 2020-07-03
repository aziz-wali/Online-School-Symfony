<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(UserRepository $userRepository)
    {
        //check if user is logged in
        if(!$this->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('app_login');
        }
        //check if the user is admin
        if(!$this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('template');
        }

         //get all students (status=0)
        $users=$this->getDoctrine()->getRepository(User::class)
            ->findBy(['status'=>0],['id'=>'DESC']);

        //get all lehrer (status=1)
        $neuelehrer=$this->getDoctrine()->getRepository(User::class)
            ->findBy(['status'=>1],['id'=>'DESC']);


        // calculate total count of  posts , students ,teachers und comments

           $count=$this->count('post','');
           $schuler=$this->count('user','WHERE status=0');
        $lehrer=$this->count('user','WHERE status=1');
         return $this->render('home/index.html.twig', [
            'users' => $users,
            'count'=>$count,
             'schuler'=>$schuler,
             'lehrer'=>$lehrer,
             'neuelehrer'=>$neuelehrer

        ]);
    }
    public function count($table,$where){
        //this function used to calculate the total numbmer of elements of a given table in the DB
        $q=" SELECT id FROM $table $where";
        $em= $this->getDoctrine()->getManager();
        $sql=$em->getConnection()->prepare($q);
        $sql->execute();
        $count=$sql->rowCount();
        return $count;
    }


}
