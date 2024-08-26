<?php
declare(strict_types=1);

namespace CleanCode\ChapterFunctions;

class PageCrawlerImpl
{
    public static function getInheritedPage(string $pageName, WikiPage $testPage): ?WikiPage
    {
        return new WikiPage();
    }
}