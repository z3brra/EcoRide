<?php

namespace App\DTO\Mail;

class MailMessageDTO
{
    /**
     * @param MailAddressDTO[] $to
     * @param MailHeaderDTO[] $headers
     * @param MailAttachmentDTO[] $attachments
     * @param MailInlineAttachmentDTO[] $inlineAttachments
     * @param MailAddressDTO[] $replyTo
     * @param MailAddressDTO[] $cc
     * @param MailAddressDTO[] $bcc
     */
    public function __construct(
        public readonly MailAddressDTO $from,
        public readonly array $to,
        public readonly string $subject,
        public readonly string $textBody,
        public readonly ?string $htmlBody = null,
        public readonly array $headers = [],
        public readonly array $attachments = [],
        public readonly array $inlineAttachments = [],
        public readonly array $replyTo = [],
        public readonly array $cc = [],
        public readonly array $bcc = [],
        public readonly ?string $returnPath = null,
    ) {}

    public function toArray(): array
    {
        $data = [
            'from' => $this->from->toArray(),
            'to' => array_map(fn (MailAddressDTO $address) => $address->toArray(), $this->to),
            'subject' => $this->subject,
            'textBody' => $this->textBody,
            'htmlBody' => $this->htmlBody ?? '',
            'headers' => array_map(fn (MailHeaderDTO $header) => $header->toArray(), $this->headers),
            'attachments' => array_map(fn (MailAttachmentDTO $attachment) => $attachment->toArray(), $this->attachments),
            'inlineAttachments' => array_map(fn (MailInlineAttachmentDTO $attachment) => $attachment->toArray(), $this->inlineAttachments),
            'replyTo' => array_map(fn (MailAddressDTO $address) => $address->toArray(), $this->replyTo),
            'cc' => array_map(fn (MailAddressDTO $address) => $address->toArray(), $this->cc),
            'bcc' => array_map(fn (MailAddressDTO $address) => $address->toArray(), $this->bcc),
            'returnPath' => $this->returnPath
        ];

        return $data;
    }
}

?>