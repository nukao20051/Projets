<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class PersonController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/person', name: 'app_person')]
    public function index(
        PersonRepository $personRepository,
        #[MapQueryParameter] string $search = '',
    ): Response {
        return $this->render('person/index.html.twig', [
            'persons' => $personRepository->search($search),
        ]);
    }

    #[Route('/person/{id}', name: 'app_person_show', requirements: ['id' => '\d+'])]
    public function show(
        #[MapEntity(expr: 'repository.findWithMedicalOffice(id)')] Person $person,
        Security $security): Response
    {
        if (!in_array('ROLE_ADMIN', $security->getUser()->getRoles()) && $security->getUser()->getEmail() != $person->getEmail()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit de consulter cette page');
        }

        return $this->render('person/show.html.twig', [
            'person' => $person,
        ]);
    }

    #[Route('/person/create', name: 'app_person_create')]
    public function create(Request $request, EntityManagerInterface $em, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator): Response
    {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $person->setPassword($this->passwordHasher->hashPassword($person, $person->getPassword()));
            $em->persist($person);
            $em->flush();

            return $userAuthenticator->authenticateUser(
                $person,
                $authenticator,
                $request
            );
        }

        return $this->render('person/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/person/{id}/update', name: 'app_person_update')]
    public function update(int $id, Request $request, EntityManagerInterface $em, PersonRepository $repository): Response
    {
        $person = $repository->find($id);
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $person->setPassword($this->passwordHasher->hashPassword($person, $person->getPassword()));
            $em->flush();

            return $this->redirectToRoute('app_person');
        }

        return $this->render('person/update.html.twig', [
            'form' => $form,
            'person' => $person,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/person/{id}/delete', name: 'app_person_delete')]
    public function delete(int $id, Request $request, EntityManagerInterface $em, PersonRepository $repository): Response
    {
        $person = $repository->find($id);
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('app_person_delete', ['id' => $id]))
            ->setMethod('POST')
            ->add('delete', SubmitType::class, [
                'label' => 'Supprimer',
            ])
            ->add('cancel', SubmitType::class, [
                'label' => 'Annuler',
                'attr' => ['class' => 'btn btn-secondary'],
            ])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->getClickedButton() && 'delete' === $form->getClickedButton()->getName()) {
                $em->remove($person);
                $em->flush();

                return $this->redirectToRoute('app_person');
            }

            return $this->redirectToRoute('app_person_show', ['id' => $id]);
        }

        return $this->render('person/delete.html.twig', [
            'form' => $form,
            'person' => $person,
        ]);
    }
}
