<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Messages;
use App\Form\MessagesType;
use App\Repository\UserRepository;
use Symfony\Component\Mercure\Update;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConversationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PublishController extends AbstractController
{
    /**
     * @Route("/message/{id}/{task},{message}", options={"expose"=true}, name="message", methods={"POST"})
     */
    public function __invoke(MessageBusInterface $bus, ConversationRepository $repoMessage, Request $request,int $id,UserRepository $repo,
    User $user,EntityManagerInterface $manager): JsonResponse
    {
        $userConnect = $this->getUser();
        $task = $request->get('task');
        //$message = $request->get('message');
        $id = $request->get('id');
        if($task == 'write'){
            //dd($request);
            $new_message = new Messages();
            $form = $this->createForm(MessagesType::class, $new_message);//Formulaire d'envoi de message
                $form->handleRequest($request); //Envoi du contenu du message dans la base 
                    $new_message->setFromId($userConnect) //Id de l'user qui envoi (envoi en base)
                            ->setToId($user)
                            ->setContent($request->request->get('message'))
                            ->setCreatedAt(new \DateTime())
                            ; //)
                    $manager->persist($new_message);
                    $manager->flush();
          }
        //dd($request);  
        //Données que l'on va utiliser dans chat.js pour l'affichage        
        $update = new Update('http://message.com/message', json_encode([
            'user' => $request->request->get('user'),
            'message' => $request->request->get('message'),
            'from' => $userConnect->getId(),
            'to' => $user->getId(),
            'userFrom' =>$userConnect->getUsername(),
          ]));
          //dd($request);  
        $bus->dispatch($update);
        return new JsonResponse(json_encode($new_message));
        //  return $this->redirectToRoute('talk_with',[
        //       'id' =>$user->getId()
        //  ]);
        //return $this->redirectToRoute('conversation');

    }
}
 