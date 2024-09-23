<?php
declare(strict_types=1);

namespace CleanCode\Fitnesse;

class PageCrawler
{
    public function getFullPath($pageName): string
    {
        return '/var/www/html/path/' . $pageName;
    }
}
