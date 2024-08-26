<?php
declare(strict_types=1);

namespace CleanCode\ChapterFunctions;

class PageCrawler {
    public function getFullPath(WikiPage $page): string
    {
        return '/var/www/html/path/page';
    }
}
