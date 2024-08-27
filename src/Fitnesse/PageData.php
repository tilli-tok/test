<?php
declare(strict_types=1);

namespace CleanCode\Fitnesse;

class PageData {
    public function getWikiPage(): WikiPage
    {
        return new WikiPage();
    }

    public function getHtml(): string
    {
        return '<html></html>';
    }

    public function hasAttribute(string $attribute): bool
    {
        return true;
    }

    public function getContent(): string
    {
        return 'Get content';
    }

    public function setContent(string $content): void
    {
    }
}