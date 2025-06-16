<?php

namespace App\Service;

use DateTimeImmutable;
use Symfony\Component\HttpFoundation\{Cookie, Request};

class AuthCookieService
{
    public function createAccessTokenCookie(string $token, Request $request): Cookie
    {
        return Cookie::create('access_token')
            ->withValue($token)
            ->withHttpOnly(true)
            ->withSecure($request->isSecure())
            ->withSameSite('strict')
            ->withExpires(new DateTimeImmutable('+7 days'));
    }

    public function revokeAccessTokenCookie(): Cookie
    {
        return Cookie::create('access_token')
            ->withValue('')
            ->withHttpOnly(true)
            ->withSecure(true)
            ->withSameSite('strict')
            ->withExpires(new DateTimeImmutable('-1 hour'));
    }
}
?>