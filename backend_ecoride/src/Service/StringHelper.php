<?php

namespace App\Service;

class StringHelper
{

    public function __construct(
        private string $emailDomain
    ) {}

    public function generateEmail(string $pseudo): string
    {
        $pseudo = str_replace('-', '_', self::normalizeString($pseudo));

        if (preg_match("/^(d'|\w{1,3})\s+/i", $pseudo, $matches)) {
            $prefix = trim($matches[1]);
            $pseudo = substr($pseudo, strlen($matches[0]));
            if ($prefix !== "d'") {
                $pseudo = str_replace(' ', '_', $prefix) . '_' . $pseudo;
            }
        }

        return str_replace(' ', '_', $pseudo) . '@'. $this->emailDomain;
    }

    private function normalizeString(string $str): string
    {
        $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
        $str = preg_replace('/[^a-zA-Z0-9 _-]/', '', $str);
        return strtolower(trim($str));
    }

    public function slugify($text) {
        $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);

        $text = preg_replace('/[^a-zA-Z0-9\s-]/', '', str_replace('_', '-', $text));
        $text = preg_replace('/[\s-]+/', '-', $text);

        $text = trim($text, '-');

        return strtolower($text);
    }
}


?>