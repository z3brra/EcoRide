<?php

namespace App\DTO\Mail;

class MailInlineAttachmentDTO
{
    public function __construct(
        public readonly string $filename,
        public readonly string $contentType,
        public readonly string $contentBase64,
        public readonly string $contentId,
    ) {}

    public function toArray(): array
    {
        return [
            'filename' => $this->filename,
            'contentType' => $this->contentType,
            'contentBase64' => $this->contentBase64,
            'contentId' => $this->contentId,
        ];
    }
}

?>