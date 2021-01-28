<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Messages;

use App\Form\MessagesType;
use App\Repository\LogoRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConversationRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Mercure\CookieGenerator;
use Symfony\Component\HttpFoundation\Response;


class ConversationController extends AbstractController
{
    /**
     * @Route("/conversation", name="conversation")
     */
    public function index(UserRepository $UserRepo,LogoRepository $logo,CookieGenerator $cookieGenerator) :Response
    {        
        $userConnect = $this->getUser()->getId(); //On récupère l'id de l'utilisateur connecté
        $members = $UserRepo->getMember($userConnect); //On utilise la fonction qui affichent les utilisateur,sauf celui connecté
        dump($members);
        $user = new User;
        $Logo = $logo->findAll();
        $response = $this->render('conversation/index.html.twig', [
            'members' => $members,
            'logo' => $Logo,
            'user' => $user
    
        ]);
        return $response;
    }


     /**
     * @Route("/conversation_show/{id}", options={"expose"=true}, name="talk_with")
     */
    public function conversation_show(ConversationRepository $repoMessage, 
    User $userConnect,Request $request,EntityManagerInterface $manager,UserRepository $repo,
    User $user,LogoRepository $logo, CookieGenerator $cookieGenerator) : Response
    {   
        
        $message = new Messages();
        $Logo = $logo->findAll();
        $userConnect = $this->getUser()->getId();//On récupère l'utilisateur courant
        $form = $this->createForm(MessagesType::class, $message);//Formulaire d'envoi de message
        $all_messages = $repoMessage->getAllMessages($this->getUser()->getId(),$user->getId());

        $members = $repo->getMember($userConnect);

        $response = $this->renderView('conversation/conversation_show.html.twig', [
            'user' => $user,
            'id_user' => $user->getId(),
            'MessagesForm' => $form->createView(),
            'Messages' => $all_messages,
            'userConnect' => $userConnect,
            'logo' => $Logo,
            'members' => $members
        ]);
        // $response->headers->setCookie($cookieGenerator->generate());
            //dd($response);
        return new Response($response);
    } 

    /**
     * @Route("/conversation_show/{id},{task},{message}/send",options={"expose"=true}, name="send")
     */
    // public function Send(ConversationRepository $repoMessage, 
    // User $userConnect,Request $request,EntityManagerInterface $manager,UserRepository $repo,
    // User $user)
    // {
        
    //     $userConnect = $this->getUser();
    //     $task = $request->get('task');
    //     $message = $request->get('message');
    //     $id = $request->get('id');
    //     if($task == 'write'){
    //         $new_message = new Messages();
    //         $form = $this->createForm(MessagesType::class, $new_message);//Formulaire d'envoi de message
    //             $form->handleRequest($request); //Envoi du contenu du message dans la base 
    //                 $new_message->setFromId($userConnect) //Id de l'user qui envoi (envoi en base)
    //                         ->setToId($user)
    //                         ->setContent($message)
    //                         ->setCreatedAt(new \DateTime())
    //                         ; //)
    //                 $manager->persist($new_message);
    //                 $manager->flush();
    //       }
    //       else{
    //         //return new JsonResponse('churros');  
    //         $all_messages = $repoMessage->getAllMessages($this->getUser()->getId(),$user->getId());
    //         return new JsonResponse($all_messages);
    //       }  
    //     return new JsonResponse($message);
    // }
}
