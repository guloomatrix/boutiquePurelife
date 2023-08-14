<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){

        $this->entityManager = $entityManager;
    }


    #[Route('/mot-de-passe-oublie', name: 'reset_password')]

    public function index(Request $request)
    {
        if ($this->getUser()){
            return $this->redirectToRoute('home');
        }

        if ($request->get('email')){
            
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));
          
            
            if($user){
                // 1 enregistrer en base la demande de reset password avec user, token , createdAt
                $reset_password= new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new \DateTimeImmutable());
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();
                //  2 Envoyer un email à l'utilisateur avec un lien permettantt de mettre à jour son mot de passe.

                $url = $this->generateUrl('update_password', [
                    'token' => $reset_password->getToken()
                ]);

                $content = "Bonjour".$user->getFirstname()."<br/> Vous avez demandez à réinitialiser votre mot de passe sur le site boutiquePurelife.<br/><br/>";
                $content .= "Merci de cliquer le lien suivant pour <a href='".$url."'>mettre à jour votre mot de passe</a>. ";
                $email = new Mail();
                $email->send($user->getEmail(), $user->getFirstname().' '.$user->getLastname(),'Réinitialiser votre mot de passe sur boutiquePurelife',$content);
                
                
                $this ->addFlash('notice', 'Vous allez recevoir dans quelques seconde un mail avec la procédure pour réinitialiser votre mot de passe');
            }   else{
                $this ->addFlash('notice', 'Cette adresse email est inconnue');
            }
        }
        return $this->render('reset_password/index.html.twig');
    }
    
    #[Route('/modifier-mon-mot-de-passe/{token}', name: 'update_password')]
   
    public function update(Request $request,$token, UserPasswordHasherInterface $hasher)

    {

        $reset_password= $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);
        if(!$reset_password){
            return $this->redirectToRoute('reset_password');
        }

        // verifier createdAt = now-3h
      
        $now = new \DateTimeImmutable();
        if($now > $reset_password->getCreatedAt()->modify('+3 hour')){
           
             $this ->addFlash('notice', 'Votre demande de mot de passe a expiré.Merci de la renouveller');
             return $this->redirectToRoute('reset_password');
        }
        
        //rendre une vue avec mot de passe et confirmer votre mot de passe
        // encodage des mots de passe
        // Flush en base de donnée
        // Redirection de l'utilisateur vers la page de connexion.
        $form =$this->createForm(ResetPasswordType::class);
        $form -> handleRequest($request);

        if ($form->isSubmitted() &&  $form ->isValid()){
            $new_pwd = $form->get('new_password')->getData();
                    // Encodage des mot de passe
                    $password = $hasher->hashPassword($reset_password->getUser(), $new_pwd);
                    $reset_password->getUser()->setPassword($password);
                        //Flush en base de donnée.         
                    $this->entityManager->flush();
                 
                  //Redirection de l'utilisateur vers la page de connexion
                $this->addFlash('notice', 'Votre mot de passe a bien été mis à jour.');
                return $this->redirectToRoute('app_login');
        }
        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView()
        ]);

           

            dd($reset_password);

    }
}
