<?php


namespace App\Service;


use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class EmailService
{

    private string $senderEmail;

    private string $senderName;

    private MailerInterface $mailer;

    /**
     * SendEmail constructor.
     * @param string $senderEmail
     * @param string $senderName
     * @param MailerInterface $mailer
     */
    public function __construct(string $senderEmail,
                                string $senderName,
                                MailerInterface $mailer)
    {
        $this->senderEmail = $senderEmail;
        $this->senderName = $senderName;
        $this->mailer = $mailer;
    }

    /**
     * @param array<mixed> $arguments
     * array["recipient_email"] string
     * array["subject"] string
     * array["html_templates"] string
     * array["context"] array
     * @return TemplatedEmail
     */
    public function generateEmail(array $arguments): TemplatedEmail
    {
        [
            "recipient_email" => $recipientEmail,
            "subject" => $subject,
            "html_templates" => $htmlTemplates,
            "context" => $context
        ] = $arguments;

        $email = new TemplatedEmail();

        return $email->from(new Address($this->senderEmail, $this->senderName))
            ->to($recipientEmail)
            ->subject($subject)
            ->context($context)
            ->htmlTemplate($htmlTemplates);
    }

    /**
     * @param array<mixed> $arguments
     * array["recipient_email"] string
     * array["subject"] string
     * array["html_templates"] string
     * array["context"] array
     */
    public function sendWithAttachement(array $arguments): void
    {
        $email = $this->generateEmail($arguments);

        ["attachement" => $attachement] = $arguments;

        //file must be initialise before E.G : fopen(path, "r")
        if(!empty($attachement)) {
            foreach ($attachement as $a) {
                $email->attach($a);
            }
        }

        try {
            $this->mailer->send($email);
        } catch (TransportException $e) {
            throw $e;
        }
    }


    /**
     * @param array<mixed> $arguments
     * array["recipient_email"] string
     * array["subject"] string
     * array["html_templates"] string
     * array["context"] array
     * @return void
     * @throws TransportException $e
     */
    public function send(array $arguments): void
    {

        $email = $this->generateEmail($arguments);

        try {
            $this->mailer->send($email);
        } catch (TransportException $e) {
            throw $e;
        }
    }
}