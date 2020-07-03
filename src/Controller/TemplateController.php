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

class TemplateController extends AbstractController
{
    /**
     * @Route("/", name="template")
     */
    public function index()
    {
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
    public function show($id,PostRepository $postRepository){
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
        $category=$categoryRepository->find($id);
        $q=" SELECT * FROM post WHERE category_id=$id";
        $stmt= $this->getDoctrine()->getConnection()->prepare($q);
        $stmt->execute();
        $videos=$stmt->fetchAll();
       //$videos=$this->getDoctrine()->getRepository(Post::class)
       // ->findBy(['category_id'=>$id],['id'=>'DESC']);

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
        return $this->render('template/about.html.twig');
    }
    /**
     * @Route("/contact",name="contact")
     */
    public function contact(){
        return $this->render('template/contact.html.twig');
    }



}
