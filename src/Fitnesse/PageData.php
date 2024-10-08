<?php
declare(strict_types=1);

namespace CleanCode\Fitnesse;

use Exception;

class PageData
{
    public string $content;

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
        return "<html lang=''>" . htmlspecialchars($this->content) . "</html>\n\n";
    }

    /**
     * @throws Exception
     * */
    public function hasAttribute(string $attribute): bool
    {
        return $this->attributes[$attribute] ?? false;
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