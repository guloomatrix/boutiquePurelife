<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;



class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    #[Route('/inscription', name: 'register')]
    
    public function index(Request $request, UserPasswordHasherInterface $hasher)
    {
                $notification = null;
                
                $user = new User();
                $form = $this->createForm(RegisterType::class, $user);
                $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()){

                $user = $form->getData();

                $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());
                if (!$search_email){
                $password = $hasher->hashPassword($user,$user->getPassword());
                $user->setPassword($password);

                $this->entityManager->persist($user);
                $this->entityManager->flush();
               
                
                $email = new Mail();
                $content = "Bonjour".$user->getFirstName()."<br> votre inscription est validée.</br>";
                $email->send($user->getEmail(), $user->getFirstName(), 'Bienvenue sur la Boutique Française', "$content");

                $notification = "Votre inscription s'est bien déroulé. Vous pouvez dès à présent vous connecter à votre compte.";
                }else {
                    $notification = "L'email que vous avez renseigné existe déjà.";
                }
        }


        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
            
            
        ]);
    }
}
