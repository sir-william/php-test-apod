<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PictureRepository;


/**
 *
 * @Route("/")
 */
class DefaultController extends AbstractController
{

    public function index(Request $request)
    {
        if ($this->get('session')->get('user')) {
            return $this->redirectToRoute('secured');
        }

        return $this->render('default/index.html.twig');
    }

    /**
     *
     * @Route("/secured",name="secured")
     */
    public function secured(Request $request, PictureRepository $pictureRepository): \Symfony\Component\HttpFoundation\Response
    {

        $user = $this->get('session')->get('user');
        if (!$user) {
            return $this->redirectToRoute('index');
        }

        $picture = $pictureRepository->findOneBy(['media_type' => 'image'], ['date' => 'DESC']);

        return $this->render('default/secured.html.twig', compact('user', 'picture'));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(Request $request): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        if ($this->get('session')->get('user')) {
            $this->get('session')->clear();
        }
        return $this->redirectToRoute('index');
    }


}
