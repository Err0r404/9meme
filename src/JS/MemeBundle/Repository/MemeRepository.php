<?php

namespace JS\MemeBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\Paginator;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class MemeRepository extends EntityRepository {

    /**
     * @param $id
     * @return array
     */
    public function getMeme($id){
        $qb = $this->createQueryBuilder('m');

        $qb
            ->andWhere('m.id = '.$id)
            ->andWhere('m.deletedAt is NULL')
        ;

        return $qb
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Paginator $paginator
     * @param Request   $request
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function getMemesPaginated(Paginator $paginator, Request $request, $userId){
        $qb = $this->createQueryBuilder('m');

        // Get memes and categories
        $qb
            ->andWhere('m.deletedAt is NULL')
            ->leftJoin('m.scores', 's')
            ->addSelect('s')
            ->andWhere('s.user = '.$userId)
            ->orderBy('m.createdAt', 'DESC')
            ->getQuery();

        $pagination = $paginator->paginate(
            $qb,    /* query NOT result */
            $request->query->getInt('page', 1),     /*page number*/
            10      /*limit per page*/
        );
        
        $pagination->setCustomParameters([
            'align' => 'center',
        ]);
        
        return $pagination;
    }
    
    /**
     * @param int $quantity
     *
     * @return array
     */
    public function getMemesRandom($quantity = 5){
        $qb = $this->createQueryBuilder('m');
        
        $qb
            ->addSelect('RAND() as HIDDEN rand')
            ->andWhere('m.deletedAt is NULL')
            ->orderBy('rand')
            ->setMaxResults($quantity)
        ;
    
        return $qb
            ->getQuery()
            ->getResult()
        ;
    
    }

    public function getMemesCreatedByUser($userId){
        $qb = $this->createQueryBuilder('m');

        $qb
            ->andWhere('m.user = '.$userId)
            ->andWhere('m.deletedAt is NULL')
            ->leftJoin('m.scores', 's')
            ->addSelect('s')
            ->andWhere('s.user = '.$userId)
            ->orderBy('m.createdAt', 'DESC')
        ;

        return $qb
            ->getQuery()
            ->getResult()
        ;
    }

    public function getMemesUpvotedByUser($userId){
        $qb = $this->createQueryBuilder('m');

        $qb
            ->andWhere('m.deletedAt is NULL')
            ->andWhere('m.user != '.$userId)
            ->leftJoin('m.scores', 's')
            ->addSelect('s')
            ->andWhere('s.user = '.$userId)
            ->andWhere('s.point = 1')
        ;

        return $qb
            ->getQuery()
            ->getResult()
        ;
    }
}