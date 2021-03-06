<?php

namespace App\Repository;

use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    //get Categories of Posts

    /**
     * @return Project[]
     */
    public function getCategories(){
        $qb = $this->createQueryBuilder('p')
            ->select('p.category')
            ->groupBy('p.category')
            ->getQuery()
            ->getScalarResult();
        return $qb;
    }
    //find projects by category and by name
    public function findProjectsByCategory($category, ?String $name){
        $qb = $this->createQueryBuilder('p');
        if(sizeof($category) > 0) {
            $qb->andWhere('p.category IN(:category)')
                ->setParameter('category', $category);
        }
        if($name){
            $qb->andWhere('p.name LIKE  :name')
                ->setParameter('name','%'.$name.'%');
        }
        return $qb->getQuery()->getResult();
    }
    public function findProjectsByUser()
    {
//        $qb = $this->createQueryBuilder('project')
//            ->innerJoin('App\Entity\ProjectMember','pM', 'WITH', 'project.id = pM.project')
////            ->where('project.id = pM.project')
//            ->Where('pM.user = :user')
//            ->setParameter('user', $user)
//            ->getQuery()
//            ->getResult();
//        return $qb;
    }
    // /**
    //  * @return Project[] Returns an array of Project objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Project
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
