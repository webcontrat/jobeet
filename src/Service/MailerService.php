<?php

namespace App\Service;

use App\Entity\Affiliate;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Templating\EngineInterface;
use Twig\Environment;

/**
 * Class MailerService
 *
 * @package App\Service
 */
class MailerService
{
    /** @var Swift_Mailer */
    private $mailer;

    /** @var EngineInterface */
    private $environment;

    /**
     * @param Swift_Mailer $mailer
     * @param Environment  $environment
     */
    public function __construct(Swift_Mailer $mailer, Environment $environment)
    {
        $this->mailer = $mailer;
        $this->environment = $environment;
    }

    /**
     * @param Affiliate $affiliate
     */
    public function sendActivationEmail(Affiliate $affiliate): void
    {
        $message = (new Swift_Message())
            ->setSubject('Account activation')
            ->setTo($affiliate->getEmail())
            ->setFrom('jobeet@example.com')
            ->setBody(
                $this->environment->render(
                    'emails/affiliate_activation.html.twig',
                    [
                        'token' => $affiliate->getToken(),
                    ]
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }
}
