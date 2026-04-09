<?php

namespace App\DTO\Contact;

use Symfony\Component\Validator\Constraints as Assert;

class ContactRequestDTO
{
    #[Assert\NotBlank(message: 'Name is required', groups: ['create'])]
    #[Assert\Length(
        min: 2,
        minMessage: 'Name must have at least 2 characters.',
        max: 100,
        maxMessage: 'Name may not exceed 100 characters.',
        groups: ['create']
    )]
    public ?string $name = null;

    #[Assert\NotBlank(message: 'Email is required.', groups: ['create'])]
    #[Assert\Email(message: 'Email must be a valid email address.', groups: ['create'])]
    #[Assert\Length(
        max: 180,
        maxMessage: 'Email may not exceed 180 characters',
        groups: ['create']
    )]
    public ?string $email = null;

    #[Assert\NotBlank(message: 'Message is required', groups: ['create'])]
    #[Assert\Length(
        min: 10,
        minMessage: 'Message must have at least 10 characters',
        max: 5000,
        maxMessage: 'Message may not exceed 5000 characters',
        groups: ['create']
    )]
    public ?string $message = null;

    public function isEmpty(): bool
    {
        return $this->name === null &&
               $this->email === null &&
               $this->message === null;
    }
}

?>