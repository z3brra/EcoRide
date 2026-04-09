<?php

namespace App\DTO\Mail;

class MailAttachmentDTO
{
    public function __construct(
        public readonly string $filename,
        public readonly string $contentType,
        public readonly string $contentBase64,
    ) {}

    public function toArray(): array
    {
        return [
            'filename' => $this->filename,
            'contentType' => $this->contentType,
            'contentBase64' => $this->contentBase64,
        ];
    }
}

?>