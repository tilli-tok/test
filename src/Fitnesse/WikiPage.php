<?php
declare(strict_types=1);

namespace CleanCode\Fitnesse;

class WikiPage
{
    public function getPageCrawler(): PageCrawler
    {
        return new PageCrawler();
    }
}