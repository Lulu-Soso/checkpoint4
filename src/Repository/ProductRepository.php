<?php

namespace App\Repository;

use App\Classe\Search;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Requete qui me permet de récupérer les produits en fonction de la recherche de l'utilisateur
     * @return Product[]
     */
    public function findWithSearch(Search $search)
    {
        // On crée une requête avec la variable $query et à l'intérieur, on va utiliser plusieurs méthodes
        $query = $this
            ->createQueryBuilder('p') // faire le mapping avec la table 'p' comme product
            ->select('c', 'p')        // 'c' comme category
            ->join('p.category', 'c'); // une jointure


        // Uniquement et lorsque les catégories ont été coché, je voudrais que tu rajoutes 
        // dans cette requête un WHERE qui me permet de filtrer mes catégories.
        if (!empty($search->categories)) {
            $query = $query
                ->andWhere('c.id IN (:categories)') // besoin que les Id de mes categories soient dans la liste des categories envoyée en paramètre dans l'objet $search
                ->setParameter('categories', $search->categories); // donner un paramètre qui aura le nom categories, et sa valeur, c'est qu'il y a dans $search->categories
        }

        // je veux que tu me retournes la query, que tu l'executes et la crée, et que tu me retournes les resultats
        return $query->getQuery()->getResult();
    }



    // /**
    //  * @return Product[] Returns an array of Product objects
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
    public function findOneBySomeField($value): ?Product
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
