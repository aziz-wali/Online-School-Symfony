<?php

namespace App\Controller;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class RegistrationController extends AbstractController
{
    /**
     * @Route("/registration", name="registration")
     */
    public function index(Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {

        $form = $this->createFormBuilder()
            ->add('email')
            ->add('password',RepeatedType::class,[
                'type'=>PasswordType::class,
                'required'=>true,
                'first_options'=>['label'=>'password'],
                'second_options'=>['label'=>'comfirm password']


                ])
            ->add('register',SubmitType::class,['attr'=>['class'=>'btn btn-primary']])
            ->getForm();
        ;
            $form->handleRequest($request);
            if($form->isSubmitted()) {
                $user = new User();
                $data = $form->getData();
                // 'var_dump($data);'
                $user->setEmail($data['email']);
                $user->setPassword(
                    $passwordEncoder->encodePassword($user, $data['password'])
            );

                $user->setRoles(array('ROLE_USER'));

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                return $this->redirectToRoute('home');
            }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
