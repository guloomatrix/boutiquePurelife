<?php

namespace App\Controller;

use App\Classe\Cart as ClasseCart;
use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
   
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/commande/merci/{stripeSessionId}', name: 'order_success')]
    
    public function index(ClasseCart $cart,$stripeSessionId)
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }
        
        if($order->getState() == 0 ) {

            $cart->remove();

            $order->setState(1);
            $this->entityManager->flush();

            $email = new Mail();
           $content = "Bonjour".$order->getUser()->getFirstname()."<br> Merci pour votre commande.<br/>";
            $email->send($order->getUser()->getEmail(),$order->getUser()->getFirstname(), 'Votre commande boutiquePurelife est bien validée.', $content);
        }
        //Modifier le statut isPaid de notre commande en mettant 1
        //Envoyer un email à notre client pour lui confirmer sa commande
        // Afficher les quelques informations de la commande de l'utilisateur

        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }
}
