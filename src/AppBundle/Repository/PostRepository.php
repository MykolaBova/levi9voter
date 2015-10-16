<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Post;
use Doctrine\ORM\Query\Expr\Join;

/**
 * This custom Doctrine repository contains some methods which are useful when
 * querying for blog post information.
 * See http://symfony.com/doc/current/book/doctrine.html#custom-repository-classes
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class PostRepository extends EntityRepository
{
    public function findLatest($limit = Post::NUM_ITEMS, $state = null, $category = null)
    {
        $builder =  $this
            ->createQueryBuilder('p')
            ->select('p')
            ->where('p.publishedAt <= :now')->setParameter('now', new \DateTime())
            ->andWhere('p.state != :draft')->setParameter('draft', Post::STATUS_DRAFT)
            ->andWhere('p.state != :review')->setParameter('review', Post::STATUS_REVIEW)
            ->orderBy('p.publishedAt', 'DESC')
            ->setMaxResults($limit);

        if (null !== $state) {
            $builder->andWhere('p.state = :state')
                ->setParameter('state', intval($state));
        }

        if (null !== $category) {
            $builder->andWhere('p.category = :category')
                ->setParameter('category', $category);
        }

        return $builder
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param User $user
     *
     * @return Post[]
     */
    public function findAccessible(User $user)
    {
        $builder = $this->createQueryBuilder('p');

        if ($user->isAdmin()) {
            $builder->where('p.state != :draft')->setParameter('draft', Post::STATUS_DRAFT);
            $builder->orWhere('p.author = :author')->setParameter('author', $user);
        } else {
            $builder->where('p.author = :author')->setParameter('author', $user);
        }

        $builder->orderBy('p.publishedAt', 'DESC');

        return $builder->getQuery()->getResult();
    }

    public function findByVoting($type, $limit = Post::NUM_ITEMS)
    {
        if ($type == Post::VOTING_MOST_RATED) {
            $postsCollection = $this->findMostRated($limit);
        } else {
            $postsCollection = $this->findMostPopular($limit);
        }

        $formattedOutput = [];

        foreach ($postsCollection as $postItem) {
            $post = reset($postItem);
            $formattedOutput[] = $post;
        }

        return $formattedOutput;
    }


    protected function findMostRated($limit)
    {
        $builder = $this
            ->createQueryBuilder('p')
            ->select('p')
            ->addSelect('SUM(v.vote) as votesRate')
            ->leftJoin('AppBundle:Vote', 'v', Join::WITH, 'v.post = p.id')
            ->where('p.state != :draft')->setParameter('draft', Post::STATUS_DRAFT)
            ->andWhere('p.state != :review')->setParameter('review', Post::STATUS_REVIEW)
            ->orderBy('votesRate', 'DESC')
            ->groupBy('p.id')
            ->setMaxResults($limit);

        return $builder
            ->getQuery()
            ->getResult();
    }

    protected function findMostPopular($limit)
    {
        $builder = $this
            ->createQueryBuilder('p')
            ->select('p')
            ->addSelect('COUNT(v.vote) as votesCount')
            ->leftJoin('AppBundle:Vote', 'v', Join::WITH, 'v.post = p.id')
            ->where('p.state != :draft')->setParameter('draft', Post::STATUS_DRAFT)
            ->andWhere('p.state != :review')->setParameter('review', Post::STATUS_REVIEW)
            ->orderBy('votesCount', 'DESC')
            ->groupBy('p.id')
            ->setMaxResults($limit);

        return $builder
            ->getQuery()
            ->getResult();
    }
}
