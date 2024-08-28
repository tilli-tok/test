<?php
declare(strict_types=1);

namespace CleanCode\Fitnesse;

class PageData {
    public $content;
    public function __construct()
    {
        $this->content = '';
    }
    public function getWikiPage(): WikiPage
    {
        return new WikiPage();
    }

    public function getHtml(): string
    {
        return '<html lang=""></html>';
    }

    public function hasAttribute(string $attribute): bool
    {
        return true;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }
    public function __toString(): string
    {
        return $this->content;
    }
}