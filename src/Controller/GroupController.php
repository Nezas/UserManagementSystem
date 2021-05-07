<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\User;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GroupController extends AbstractController
{
    /**
     * @Route("/groups", name="groups")
     */
    public function index(GroupRepository $groupRepository): Response
    {
        $groups = $groupRepository->findAll();
        return $this->render('group/index.html.twig', [
            'groups' => $groups
        ]);
    }

    /**
     * @Route("/create_group", name="create_group")
     */
    public function create_group()
    {
        return $this->render('group/create_group.html.twig');
    }

    /**
     * @Route("/group_list", name="group_list")
     */
    public function create(Request $request, GroupRepository $groupRepository)
    {
        $group = new Group();

        $group->setName($request->get('name'));
        $em = $this->getDoctrine()->getManager();

        $em->persist($group);
        $em->flush();

        $groups = $groupRepository->findAll();
        return $this->render('group/index.html.twig', [
            'groups' => $groups
        ]);
    }

    /**
     * @Route("/delete_group", name="delete_group")
     */
    public function delete_group(Request $request, GroupRepository $groupRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $group = $em->getRepository(Group::class)->find($request->get('id'));
        $em->remove($group);
        $em->flush();

        $groups = $groupRepository->findAll();
        return $this->render('group/index.html.twig', [
            'groups' => $groups
        ]);
    }

    /**
     * @Route("/remove_user", name="remove_user")
     */
    public function remove_user(Request $request, GroupRepository $groupRepository, UserRepository $userRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $group = $em->getRepository(Group::class)->find($request->get('groupId'));
        $user = $em->getRepository(User::class)->findOneBy(['name' => $request->get('userName')]);
        $user->setGroupName('');
        $group->removeUser($request->get('userId'));
        $em->flush();

        $groups = $groupRepository->findAll();
        return $this->render('group/index.html.twig', [
            'groups' => $groups
        ]);
    }
}
