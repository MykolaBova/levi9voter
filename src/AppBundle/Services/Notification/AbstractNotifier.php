<?php

namespace AppBundle\Services\Notification;

use AppBundle\Services\MailerService;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class AbstractNotifier
 */
abstract class AbstractNotifier
{
    /**
     * @var MailerService
     */
    protected $mailer;

    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @param MailerService           $mailer
     * @param EngineInterface         $templating
     * @param TranslatorInterface $translator
     */
    public function __construct(MailerService $mailer, EngineInterface $templating, TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->translator = $translator;
    }

    /**
     * Render a view
     *
     * @param string $view
     * @param array  $parameters
     *
     * @return string
     *
     * @throws \Exception
     * @throws \Twig_Error
     */
    protected function renderView($view, array $parameters = array())
    {
        return $this->templating->render($view, $parameters);
    }
}
