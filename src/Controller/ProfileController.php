<?php

namespace App\Controller;

use App\Form\ProfileType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function createProfile(Request $request, UserRepository $userRepository): Response
    {
        $profile = $userRepository->findOneBy([
            'id' => $this->getUser(),
        ]);
        $form = $this->createForm(ProfileType::class, $profile);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($profile, true);

            return $this->redirect('/profile');
        }

        return $this->render('profile/index.html.twig', [
            'form' => $form,
        ]);
    }
}
