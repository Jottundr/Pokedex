<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Form\ProfileType;
use App\Repository\ProfileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function createProfile(Request $request, ProfileRepository $profileRepository): Response
    {   
        $user = $this->getUser();
        $profile = $profileRepository->findOneBy(['id' => $user]);
        if (!$profile) {
            $profile = new Profile();
            $form = $this->createForm(ProfileType::class, $profile);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $profileRepository->save($profile, true);
            }
        }       
        $form = $this->createForm(ProfileType::class, $profile);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($profile->getUserId() == null) {
                $profile->setUserId($this->getUser());
            } else
            $profileRepository->save($profile, true);
        }

        return $this->render('profile/index.html.twig', [
            'form' => $form,
        ]);
    }

}
