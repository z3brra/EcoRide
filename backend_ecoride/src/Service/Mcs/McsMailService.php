<?php

namespace App\Service\Mcs;

use App\DTO\Mail\{
    MailMessageDTO,
    MailSendResultDTO
};

class McsMailService
{
    public function __construct(
        private McsClient $mcsClient,
    ) {}

    public function send(MailMessageDTO $message): MailSendResultDTO
    {
        $response = $this->mcsClient->post(
            '/token/mail/send',
            $message->toArray()
        );

        return MailSendResultDTO::fromArray($response['data']);
    }
}

?>