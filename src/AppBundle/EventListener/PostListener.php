<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Post;
use AppBundle\Event\PostEvent;
use AppBundle\Services\Notification\PostNotifier;

/**
 * Class PostListener
 */
class PostListener
{
    /**
     * @var PostNotifier
     */
    private $notifier;

    /**
     * @param PostNotifier $notifier
     */
    public function __construct(PostNotifier $notifier)
    {
        $this->notifier = $notifier;
    }

    /**
     * @param PostEvent $event
     */
    public function onPostStatusChange(PostEvent $event)
    {
        /**
         * @var Post
         */
        $post = $event->getPost();
        switch ($post->getState()) {
            case Post::STATUS_REVIEW:
                $this->notifier->sendPostReviewEmail($post, $this->adminEmails);
                break;
            case Post::STATUS_VOTING:
                $this->notifier->sendPostVotingEmail($post);
                break;
            case Post::STATUS_APPROVED:
                $this->notifier->sendPostVoteApprovedEmail($post);
                break;
            case Post::STATUS_REJECTED:
                $this->notifier->sendPostVoteRejectedEmail($post);
                break;
        }
    }
}
