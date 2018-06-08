<?php
/**
 * PhalconSlayer\Framework.
 *
 * @copyright 2015-2016 Daison Carino <daison12006013@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://docs.phalconslayer.com
 */

/**
 */
namespace Components\Clarity\Mail\SwiftMailer;

use Swift_Mailer;
use Swift_Message;
use Swift_Attachment;
use Swift_SmtpTransport;
use Components\Clarity\Contracts\Mail\MailInterface;

class SwiftMailer implements MailInterface
{
    private $message;
    private $transport;

    public function __construct()
    {
        $this->message = Swift_Message::newInstance();
        $this->transport = Swift_SmtpTransport::newInstance();
    }

    public function attach($file)
    {
        $this->message->attach(
            Swift_Attachment::fromPath($file)
        );

        return $this;
    }

    public function encryption($encryption)
    {
        $this->transport->setEncryption($encryption);

        return $this;
    }

    public function host($host)
    {
        $this->transport->setHost($host);

        return $this;
    }

    public function port($port)
    {
        $this->transport->setPort($port);

        return $this;
    }

    public function username($username)
    {
        $this->transport->setUsername($username);

        return $this;
    }

    public function password($password)
    {
        $this->transport->setPassword($password);

        return $this;
    }

    public function from($email)
    {
        $this->message->setFrom($email);

        return $this;
    }

    public function to(array $emails)
    {
        $this->message->setTo($emails);

        return $this;
    }

    public function bcc(array $emails)
    {
        $this->message->setBcc($emails);

        return $this;
    }

    public function cc(array $emails)
    {
        $this->message->setBcc($emails);

        return $this;
    }

    public function subject($subject)
    {
        $this->message->setSubject($subject);

        return $this;
    }

    public function body($body)
    {
        $this->message->addPart($body, 'text/html');

        return $this;
    }

    public function send()
    {
        $mailer = Swift_Mailer::newInstance($this->transport);

        return $mailer->send($this->message);
    }
}
