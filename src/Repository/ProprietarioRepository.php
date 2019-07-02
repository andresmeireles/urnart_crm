<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Proprietario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Proprietario|null find($id, $lockMode = null, $lockVersion = null)
 * @method Proprietario|null findOneBy(array $criteria, array $orderBy = null)
 * @method Proprietario      findAll()
 * @method Proprietario      findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProprietarioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Proprietario::class);
    }

//    /**
//     * @return Proprietario[] Returns an array of Proprietario objects
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
    public function findOneBySomeField($value): ?Proprietario
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
