<?php

namespace AppBundle\Services;

class MailerService
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer, array $config)
    {
        $this->mailer = $mailer;
        $this->config = $config;
    }

    /**
     * @param mixed  $to
     * @param string $subject
     * @param string $body
     *
     * @return int
     */
    public function send($to, $subject, $body)
    {
        return $this->mailer->send(
            $this->createMessage($to, $subject, $body)
        );
    }

    /**
     * Create a new message using following parameters
     *
     * @param mixed  $to
     * @param string $subject
     * @param string $body
     *
     * @return \Swift_Message
     */
    private function createMessage($to, $subject, $body)
    {
        return \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($this->getConfig('from', 'no-reply@levi9.com'))
            ->setTo($to)
            ->setBody($body, 'text/html', 'utf-8');
    }

    /**
     * Get config by name
     *
     * @param $name
     * @param $default
     *
     * @return mixed
     */
    protected function getConfig($name, $default)
    {
        return isset($this->config[$name]) ? $this->config[$name] : $default;
    }
}
