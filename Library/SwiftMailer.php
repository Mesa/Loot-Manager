<?php

/**
 * Wrapper class for swift mailer.
 */

class SwiftMailer
{

    protected $transport_obj = null;
    protected $message_obj = null;
    protected $mailer_obj = null;

    protected $from_mail = null;
    protected $from_name = null;

    public function __construct (\JackAssPHP\Core\Registry $registry )
    {
        $this->from_mail = $registry->get("SMTP_FROMMAIL");
        $this->from_name = $registry->get("SMTP_FROMNAME");
        $mode = strtolower($registry->get("MAIL_MODE"));

        switch ($mode)
        {
        case "smtp":
            $this->transport_obj = Swift_SmtpTransport::newInstance(
                    $registry->get("SMTP_HOST"),
                    465, 'ssl'
                )
                ->setUsername($registry->get("SMTP_USERNAME"))
                ->setPassword($registry->get("SMTP_PASSWORD"));
        break;
        case "sendmail":
            $this->transport_obj = Swift_SendmailTransport::newInstance();
        break;
        $this->transport_obj = Swift_MailTransport::newInstance();
        default:
        }

        $this->mailer_obj = Swift_Mailer::newInstance($this->transport_obj);
    }

    public function send ($to, $subject, $body)
    {
        if ( is_array($to)) {
            $this->message_obj = Swift_Message::newInstance($subject, $body, "text/html")
                    ->setTo($to)
                    ->setSender(array($this->from_mail => $this->from_name));
            return $this->mailer_obj->send($this->message_obj);
        } else {
            return false;
        }
    }

}