<?php
namespace User\Mail;

use Mail\NakadeMail;
use Mail\Services\MailMessageFactory;
use User\Entity\User;
use \Zend\Mail\Transport\TransportInterface;

/**
 * Class UserMail
 *
 * @package User\Mail
 */
abstract class UserMail extends NakadeMail
{
    protected $url = 'http://www.nakade.de';
    protected $user;
    protected $plainPwd;
    protected $verifyUrl;

     /**
     * @param MailMessageFactory $mailService
     * @param TransportInterface $transport
     */
    public function __construct(MailMessageFactory $mailService, TransportInterface $transport)
    {
        $this->mailService = $mailService;
        $this->transport = $transport;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $plainPwd
     */
    public function setPlainPwd($plainPwd)
    {
        $this->plainPwd = $plainPwd;
    }

    /**
     * @return string
     */
    public function getPlainPwd()
    {
        return $this->plainPwd;
    }

    /**
     * @param mixed $verifyUrl
     */
    public function setVerifyUrl($verifyUrl)
    {
        $this->verifyUrl = $verifyUrl;
    }

    /**
     * @return mixed
     */
    public function getVerifyUrl()
    {
        return $this->verifyUrl;
    }

    //todo verify link; no logic in this service!!!
//$email          = $this->params()->fromQuery('email', null);
//$verifyString   = $this->params()->fromQuery('verify', null);




    protected function makeReplacements(&$message)
    {
        $message = str_replace('%URL%', $this->getUrl(), $message);

        if (!is_null($this->getUser())) {

            $link = sprintf('%s/verify?email=%s&verify=%s',
                $this->getUrl(),
                $this->getUser()->getEmail(),
                $this->getUser()->getVerifyString()
            );

            $message = str_replace('%FIRST_NAME%', $this->getUser()->getFirstName(), $message);
            $message = str_replace('%USERNAME%', $this->getUser()->getUsername(), $message);
            $message = str_replace('%PASSWORD%', $this->getPlainPwd(), $message);
            $message = str_replace('%VERIFY_LINK%', $link, $message);
            $message = str_replace('%DUE_DATE%', $this->getUser()->getDue()->format('d.m.y H:i'), $message);
        }
    }

}