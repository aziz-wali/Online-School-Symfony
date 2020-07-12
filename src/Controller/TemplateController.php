<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tags;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

//use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
//use Doctrine\Persistence\ManagerRegistry;

class TemplateController extends AbstractController
{
    /**
     * @Route("/", name="template")
     */
    public function index()
    { //this function usted to display the Home page that user will see.

        if(isset($_GET['limit'])){
            $limit=$_GET['limit'];
        }else{
            $limit=6;
        }

        $videos = $this->getDoctrine()->getRepository(Post::class)
            ->findBy([],['id'=>'DESC'],$limit);

        $categories=$this->getDoctrine()->getRepository(Category::class)->findAll();
        $users=$this->getDoctrine()->getRepository(User::class)->findAll();
        $tags=$this->getDoctrine()->getRepository(Tags::class)->findAll();

        return $this->render('template/index.html.twig', [
            'videos' => $videos,
            'categories' => $categories,
            'users'    =>$users,
            'tags' => $tags
        ]);
    }
    /**
     * @Route("/single/{id}", name="single")
     */
    public function show($id,PostRepository $postRepository)
    {
    //this function usted to display One Video or Post according to the id
        $video=$postRepository->find($id);
        return $this->render('template/single.html.twig',[
            'video'=>$video
        ]);
    }
    /**
     * @Route("/category/{id}", name="category")
     */
    public function category($id,CategoryRepository $categoryRepository,PostRepository $postRepository)
    {
        //to get all posts or video that have the same category

        $category=$categoryRepository->find($id);
        $em=$this->getDoctrine()->getManager();
        $videos=$em->getRepository(Post::class)->createQueryBuilder('p')
            ->andWhere('p.category = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
        return $this->render('template/category.html.twig',[
            'category'=>$category,
            'videos'=>$videos
        ]);
    }


    /**
     * @Route("tag/{id}",name="tag")
     */
    public function tag($id)
    {
        //to get all posts or video that have the same tag
        $ta=$this->getDoctrine()->getRepository(Tags::class)->find($id);
        $posts = $this->getDoctrine()->getRepository(Post::class)
            ->findAll();
 return $this->render('template/tag.html.twig',[
                'tag'=>$ta,
                 'posts'=>$posts
        ]);
    }
    /**
     * @Route("/about",name="about")
     */
    public function about()
    {
        //display about page
        return $this->render('template/about.html.twig');
    }
    /**
     * @Route("/contact",name="contact")
     */
    public function contact(){
        //show contact page
        return $this->render('template/contact.html.twig');
    }



}
