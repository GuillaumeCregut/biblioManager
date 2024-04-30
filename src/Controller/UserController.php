<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PassType;
use App\Form\UserType;
use App\Services\PictureConvert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user', name: 'user_')]
#[IsGranted('ROLE_USER')]
class UserController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('user/index.html.twig');
    }

    #[Route('/edit', name: 'edit')]
    public function edit(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $userPasswordHasher,
    ): Response {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        //Check form
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $checkPassword = $request->get('user')['checkPassword'] ?? '';
            if ($userPasswordHasher->isPasswordValid($user, $checkPassword)) {
                /**
                 * @var \Symfony\Component\HttpFoundation\File\UploadedFile $avatar
                 */
                $avatar = $form->get('avatarUpload')->getData();
                if ($avatar !== null && $avatar->getError() === 0) {
                    $newAvatar = PictureConvert::convert($avatar, 100);
                    /**
                     * @var string $pictureDirectory
                     */
                    $pictureDirectory = $this->getParameter('app.dir.avatar');
                    $fileName = $pictureDirectory . DIRECTORY_SEPARATOR .
                        (string)$user->getId() . DIRECTORY_SEPARATOR . 'avatar.png';
                    imagepng($newAvatar, $fileName);
                    imagedestroy($newAvatar);
                    $user->setAvatar('avatar.png');
                }
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'Modifications effectuées');
                return $this->redirectToRoute('user_index');
            } else {
                $field = $form->get('checkPassword');
                $field->addError(new FormError('Mot de passe incorrect'));
            }
        }
        return $this->render('user/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/password', name: 'password')]
    public function editPassword(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em
    ): Response {
        $form = $this->createForm(PassType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $datas = $form->getData();
            if ($datas['new_password'] !== $datas['confirm_password']) {
                $field = $form->get('confirm_password');
                $field->addError(new FormError('Les mots de passe ne sont pas identiques'));
            } else {
                /**
                 * @var User $user
                 */
                $user = $this->getUser();
                $checkPassword = $datas['old_password'];
                $verify = $passwordHasher->isPasswordValid($user, $checkPassword);
                if ($verify) {
                    $user->setPassword(
                        $passwordHasher->hashPassword(
                            $user,
                            $datas['new_password']
                        )
                    );
                    $em->persist($user);
                    $em->flush();
                    $this->addFlash('success', 'Modifications effectuées');
                    return $this->redirectToRoute('user_index');
                } else {
                    $field = $form->get('old_password');
                    $field->addError(new FormError('Mot de passe incorrect'));
                }
            }
        }
        return $this->render('user/editPassword.html.twig', [
            'form' => $form
        ]);
    }
}
