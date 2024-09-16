<?php
declare(strict_types=1);

namespace CleanCode\Fitnesse;

class PageCrawlerImpl
{
    public static function getInheritedPage(): ?WikiPage
    {
        return new WikiPage();
    }
}