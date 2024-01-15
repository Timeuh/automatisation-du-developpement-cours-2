<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MyExtension extends AbstractExtension
{
    public function getName(): string
    {
        return 'my-extension';
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('hashString', [$this, 'hashString']),
            new TwigFilter('myReplace', [$this, 'myReplace']),
            new TwigFilter('getAssetUrl', [$this, 'getAssetUrl'])
        ];
    }

    public function hashString(string $string): string
    {
        return hash('sha256', $string);
    }

    public function myReplace(string $string, string $search, string $replace): string
    {
        return str_replace($search, $replace, $string);
    }

    public function getAssetUrl(): string
    {
        if ($_ENV['ENV'] == "prod") {
            return '/';
        }

        return 'http://localhost:3000/';
    }
}
