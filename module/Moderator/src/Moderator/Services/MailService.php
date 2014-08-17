<?php

namespace Moderator\Services;

use Moderator\Mail;
use Mail\Services\AbstractMailService;

/**
 * Class MailService
 *
 * @package Moderator\Services
 */
class MailService extends AbstractMailService
{

    const REPLY_INFO_MAIL = 'reply_info';
    const STAGE_CHANGED_MAIL = 'stage';

    /**
     * @param string $typ
     *
     * @return \Moderator\Mail\SupportMail
     *
     * @throws \RuntimeException
     */
    public function getMail($typ)
    {
        switch (strtolower($typ)) {

            case self::REPLY_INFO_MAIL:
                $mail = new Mail\ReplyInfoMail($this->getMessage(), $this->getTransport());
                break;

            case self::STAGE_CHANGED_MAIL:
                $mail = new Mail\StageChangedMail($this->getMessage(), $this->getTransport());
                break;

            default:
                throw new \RuntimeException(
                    sprintf('An unknown mail type was provided.')
                );
        }

        $mail->setTranslator($this->getTranslator());
        $mail->setTranslatorTextDomain($this->getTextDomain());
        $mail->setSignature($this->getSignature());

        return $mail;
    }

    /**
     * @return mixed
     */
    public function getTextDomain()
    {
        if (is_null($this->textDomain)) {
            $config  = $this->getConfig();
            if (isset($config['Moderator']['text_domain'])) {
                $this->textDomain = $config['Moderator']['text_domain'];
            }
        }
        return $this->textDomain;
    }


}
