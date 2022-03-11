<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\Google;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GoogleController extends AbstractController
{

    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/google", name="connect_google_start")
     */
    public function connectAction(ClientRegistry $clientRegistry): RedirectResponse
    {
        if ($this->get('session')->get('user')) {
            return $this->redirectToRoute('secured');
        }
        return $clientRegistry
            ->getClient('google')->redirect(['email']); // the scopes you want to access
    }

    /**
     * After going to google, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     * @Route("/connect/google/check", name="connect_google_check")
     */
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry): RedirectResponse
    {

        /** @var GoogleClient $client */
        $service = implode($clientRegistry->getEnabledClientKeys());
        $client = $clientRegistry->getClient($service);


        try {
            /** @var GoogleClient $user */
            $user = $client->fetchUser();
            $this->get('session')->set('user', $user);
            return $this->redirectToRoute('secured');
        } catch (IdentityProviderException $e) {
            var_dump($e->getMessage());
            die;
        }
    }

}
