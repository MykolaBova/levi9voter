<?php

namespace AppBundle\Services\Notification;

use AppBundle\Entity\Post;
use AppBundle\Entity\Comment;

/**
 * Class PostNotifier
 */
class PostNotifier extends AbstractNotifier
{
    /**
     * @var array
     */
    private $adminEmails;

    /**
     * @param $adminEmails
     */
    public function setAdminEmails($adminEmails)
    {
        $this->adminEmails = $adminEmails;
    }

    /**
     * Send email to admins on vote in review
     *
     * @param Post  $post
     */
    public function sendPostReviewEmail(Post $post)
    {
        $subject = $this->translator->trans('mail.vote.review');
        $body = $this->renderView('mail/post_review.html.twig', array('post' => $post));

        $this->mailer->send($this->adminEmails, $subject, $body);
    }

    /**
     * Send email to author on his vote published
     *
     * @param Post $post
     */
    public function sendPostVotingEmail(Post $post)
    {
        $this->mailer->send(
            $post->getAuthor()->getEmail(),
            $this->translator->trans('mail.vote.published'),
            $this->renderView('mail/post_voting.html.twig', array('post' => $post))
        );
    }

    /**
     * @param Post $post
     */
    public function sendPostVoteApprovedEmail(Post $post)
    {
        $this->sendPostVoteClosedEmail($post, 'mail.vote.approved', 'mail/post_approved.html.twig');
    }

    /**
     * @param Post $post
     */
    public function sendPostVoteRejectedEmail(Post $post)
    {
        $this->sendPostVoteClosedEmail($post, 'mail.vote.rejected', 'mail/post_rejected.html.twig');
    }

    /**
     * Send email to author and all people from comments when vote is Approved or Rejected
     *
     * @param Post   $post
     * @param string $translation
     * @param string $template
     */
    private function sendPostVoteClosedEmail(Post $post, $translation, $template)
    {
        $recipients[] = $post->getAuthor()->getEmail();
        /** @var Comment $comment */
        $comments = $post->getComments();
        foreach ($comments as $comment) {
            $recipients[] = $comment->getUser()->getEmail();
        }

        $subject = $this->translator->trans($translation);
        $body = $this->renderView($template, array('post' => $post));

        $this->mailer->send($recipients, $subject, $body);
    }
}
