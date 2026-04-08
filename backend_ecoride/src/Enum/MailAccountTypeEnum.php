<?php

namespace App\Enum;

enum MailAccountTypeEnum: string
{
    case MAILBOX = 'mailbox';
    case ALIAS = 'alias';
}

?>