<?php
declare(strict_types=1);

namespace CleanCode\ChapterFunctions;

class WikiPage {
    public function getPageCrawler(): PageCrawler
    {
        return new PageCrawler();
    }
}