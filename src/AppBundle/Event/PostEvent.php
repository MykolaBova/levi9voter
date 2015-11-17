<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use AppBundle\Entity\Post;

/**
 * Class PostEvent
 */
class PostEvent extends Event
{
    /**
     * @var Post
     */
    protected $post;

    /**
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }
}
