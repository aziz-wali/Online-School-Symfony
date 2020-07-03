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
        if(!$this->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('app_login');
        }
        if(!$this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('template');
        }


        $users=$this->getDoctrine()->getRepository(User::class)
            ->findBy(['status'=>0],['id'=>'DESC']);
        $neuelehrer=$this->getDoctrine()->getRepository(User::class)
            ->findBy(['status'=>1],['id'=>'DESC']);


        // rechnen die nummer von posts , lehrer ,schueler und kommentare

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

        $q=" SELECT id FROM $table $where";
        $em= $this->getDoctrine()->getManager();
        $sql=$em->getConnection()->prepare($q);
        $sql->execute();
        $count=$sql->rowCount();
        return $count;
    }


}
