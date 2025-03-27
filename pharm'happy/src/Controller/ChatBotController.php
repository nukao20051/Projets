<?php

namespace App\Controller;

use App\Repository\MedicationRepository;
use GeminiAPI\Client;
use GeminiAPI\Resources\ModelName;
use GeminiAPI\Resources\Parts\TextPart;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatBotController extends AbstractController
{
    private LoggerInterface $logger;
    private Client $client;
    private string $medications;

    public function __construct(LoggerInterface $logger, MedicationRepository $medicationRepository)
    {
        $this->logger = $logger;
        $this->client = new Client('AIzaSyAuOXpmtbuAo2lmOsb8im_I7Fz3eH_wogs');
        $this->medications = '';
        foreach ($medicationRepository->findAll() as $medication) {
            $this->medications .= $medication->getId().' :'.$medication->getName();
        }
    }

    #[Route('/chatbot', name: 'app_chat_bot', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('chatbot/index.html.twig');
    }

    #[Route('/chatbot/getResponse', name: 'app_chat_bot_response', methods: ['GET'])]
    public function getResponse(Request $request): Response
    {
        $message = $request->query->get('message');

        if (!$message) {
            return new JsonResponse(['error' => 'Message parameter is missing'], Response::HTTP_BAD_REQUEST);
        }

        $this->logger->info('Received message: '.$message);

        try {
            $response = $this->client->withV1BetaVersion()
                ->generativeModel(ModelName::GEMINI_1_5_FLASH)
            ->withSystemInstruction('Tu es PharmBot, un professionnel de santé ainsi que 
                le robot du site Pharm\'Happy, ton rôle sera de répondre aux questions des clients concernant 
                notre pharmacie ainsi que la santé en général, ne dis pas que tu n\'es pas un expert, réponds systématiquement si c\'est une question de santé. La liste de nos médicaments est: '.$this->medications
                .'si tu peux, essaye de mettre un lien vers <a href="medication/{id}"> pour plus d\'informations sur un médicament')
                ->generateContent(
                    new TextPart($message),
                );

            $responseText = $response->text();
            $this->logger->info('Generated response: '.$responseText);

            return new JsonResponse(['text' => $responseText]);
        } catch (\Exception $e) {
            $this->logger->error('Error generating response: '.$e->getMessage());

            return new JsonResponse(['error' => 'Failed to generate response'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
