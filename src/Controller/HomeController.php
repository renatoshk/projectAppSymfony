<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\ProjectMember;
use App\Entity\User;
use App\Form\AddProjectFormType;
use App\Form\PersonRoleFormType;
use App\Form\SearchDataFormType;
use App\Repository\ProjectMemberRepository;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="homepage")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if($user){
            $roles = $user->getRoles();
            foreach ($roles as $role) {
                if($role == 'ROLE_ADMIN'){
                   $allProjects = $this->getDoctrine()->getRepository(Project::class)
                       ->findAll();
                    $projectData = [];
                   foreach($allProjects as $project){
                       if (!isset($projectData[''.$project->getId()])){
                           $projectData[''.$project->getId()] = [];
                       }
                       $projectMembers = $this->getDoctrine()->getRepository(ProjectMember::class)
                           ->getMembersOfProject($project);
                       $projectData[''.$project->getId()]['project'] = $project;
                       $projectData[''.$project->getId()]['members'] = $projectMembers;
                   }
                    return $this->render('admin/index.html.twig', [
                        'controller_name' => 'HomeController',
                        'projectsData' => $projectData
                    ]);
                }
                else {
                    $projectMembers = $this->getDoctrine()->getRepository(ProjectMember::class)
                        ->findProjectsByUser($user);
                    return $this->render('home/index.html.twig', [
                        'controller_name' => 'HomeController',
                        'projects' => $projectMembers,
                    ]);
                }
            }
        }
        else {
            return $this->redirectToRoute('app_login');
        }
    }
    /**
     * @Route ("/create", name="create_project")
     */
    public function addProject(Request $request){
        $em = $this->getDoctrine()->getManager();
        $project = new Project();
        $user = $this->getUser();
        $form = $this->createForm(AddProjectFormType::class, $project);
        if($user) {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                $member = new ProjectMember();
                $member->setProject($project);
                $member->setUser($user);
                $member->setProjectRole('admin');
                $em->persist($project);
                $em->persist($member);
                $em->flush();
                return $this->redirectToRoute('homepage');
            }
        }
        return $this->render('home/new.html.twig', [
            'formProject' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/add-role-to-project", name="add_role_to_project")
     */
    public function addRoleToProject(Project $project, Request $request)
    {
        $user = $this->getUser();
        if ($user) {
            //get User Role
            $roles = $user->getRoles();
            foreach ($roles as $role) {
                if($role == 'ROLE_USER'){
                   $projectsMember = $this->getDoctrine()->getRepository(ProjectMember::class)
                       ->findProjectsByUser($user);
                   foreach($projectsMember as $projectMember){
                      if($projectMember->getProject()->getId() !== $project->getId()){
                          return $this->redirectToRoute('homepage');
                      }
                   }
                }
                $newMember = new ProjectMember();
                $form = $this->createForm(PersonRoleFormType::class, $newMember);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $newMember->setProject($project);
                    $this->getDoctrine()->getManager()->persist($newMember);
                    $this->getDoctrine()->getManager()->flush();
                    return $this->redirectToRoute('homepage');
                }
                return $this->render('home/addPersonRoleToProject.html.twig', [
                    'project' => $project,
                    'projectMember' => $form->createView()
                ]);
            }
        }
        else {
            return $this->redirectToRoute('app_login');
        }
    }
    /**
     * @Route("/remove-member", name="remove_member_from_project")
     */
    public function removeMemberFromProject(Request $request){
         $projectMemberId = $request->get('id');
         $em = $this->getDoctrine()->getManager();
         $projectMember = $em->getRepository(ProjectMember::class)->find($projectMemberId);
         if($projectMember){
             $em->remove($projectMember);
             $em->flush();
             return new JsonResponse(['code'=>'success', 'message'=>'Removed!']);
         }
         else{
             return new JsonResponse(['code'=>'error', 'message'=>'Unable to find that member!']);
         }
    }

    /**
     * @Route("/search-to-project", name="search_index")
     */
    public function displayDataToSearchFile(Request $request){
        $form = $this->createForm(SearchDataFormType::class);

        return $this->render('admin/search.html.twig', [
            'formSearch' => $form->createView()
        ]);
    }
}
