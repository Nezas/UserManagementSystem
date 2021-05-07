<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Group;
use App\Repository\UserRepository;
use App\Repository\GroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/add_user", name="add_user")
     */
    public function add_user()
    {
        return $this->render('user/add_user.html.twig');
    }

    /**
     * @Route("/users", name="users")
     */
    public function add(Request $request, UserRepository $userRepository)
    {
        $user = new User();

        $user->setName($request->get('name'));
        $user->setGroupName('');
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $users = $userRepository->findAll();
        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/delete", name="delete")
     */
    public function delete(Request $request, UserRepository $userRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($request->get('id'));
        if (!$user) {
            throw $this->createNotFoundException(
                'No user found'
            );
        }
        $em->remove($user);
        $em->flush();

        $users = $userRepository->findAll();
        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/assign", name="assign")
     */
    public function assign(Request $request, GroupRepository $groupRepository)
    {
        $groups = $groupRepository->findAll();
        $userId = $request->get('id');
        return $this->render('group/assign_group.html.twig', [
            'groups' => $groups,
            'userId' => $userId,
        ]);
    }

    /**
     * @Route("/assign_to_group", name="assign_to_group")
     */
    public function assign_to_group(Request $request, GroupRepository $groupRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $group = $em->getRepository(Group::class)->find($request->get('id'));
        $userId = $request->get('userId');
        $user = $em->getRepository(User::class)->find($userId);
        $userName = $user->getName();
        if ($group->findUser($userName)) {
            $groups = $groupRepository->findAll();
            return $this->render('group/index.html.twig', [
                'groups' => $groups
            ]);
        }
        $user->setGroupName($group->getName());
        $group->setUser($userName);
        $em->flush();

        $groups = $groupRepository->findAll();
        return $this->render('group/index.html.twig', [
            'groups' => $groups
        ]);
    }
}
