<?php

namespace App\Repository;

use App\Entity\PessoaJuridica;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PessoaJuridica|null find($id, $lockMode = null, $lockVersion = null)
 * @method PessoaJuridica|null findOneBy(array $criteria, array $orderBy = null)
 * @method PessoaJuridica[]    findAll()
 * @method PessoaJuridica[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PessoaJuridicaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PessoaJuridica::class);
    }

//    /**
//     * @return PessoaJuridica[] Returns an array of PessoaJuridica objects
//     */
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
    public function findOneBySomeField($value): ?PessoaJuridica
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
