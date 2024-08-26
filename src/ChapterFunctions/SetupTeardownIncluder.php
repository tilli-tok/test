<?php

declare(strict_types=1);

namespace CleanCode\ChapterFunctions;


//namespace FitNesse\Html;
//use FitNesse\Responders\Run\SuiteResponder;
//use FitNesse\Wiki;

/**
package fitnesse.html;
import fitnesse.responders.run.SuiteResponder;
import fitnesse.wiki.*;
 */
public class SetupTeardownIncluder{
    private PageData $pageData;
    private bool $isSuite;
    private WikiPage $testPage;
    private string $newPageContent;
    private PageCrawler $pageCrawler;

    /**
     * @param PageData $pageData
     * @param bool $isSuite
     * @return string
     */
 /**
    public static function render(PageData $pageData)
    {
        return render($pageData);
    }
*/

    public static function render(PageData $pageData, bool $isSuite = false): string
    {
        return (new self($pageData))->renderHTML($isSuite);
    }

    private function __construct(PageData $pageData)
    {
        $this->pageData = $pageData;
        $this->testPage = $pageData->getWikiPage();
        $this->pageCrawler = $this->testPage->getPageCrawler();
        $this->newPageContent = StringBuffer();
    }
    private function renderHTML(bool $isSuite): string
    {
        $this->isSuite = $isSuite;
        if ($this->isTestPage()) {
            $this->includeSetupAndTeardownPages();
        }
        return $this->pageData->getHtml();
    }

    private function isTestPage(): bool
    {
        return $this->pageData->hasAttribute('Test');
    }
    private function includeSetupAndTeardownPages(): void
    {
        $this->includeSetupPages();
        $this->includePageContent();
        $this->includeTeardownPages();
        $this->updatePageContent();
    }
    private function includeSetupPages(): void
    {
        if ($this->isSuite) {
            $this->includeSuiteSetupPage();
            $this->includeSetupPage();
        }

    }
    private function includeSuiteSetupPage(): void
    {
        $this->include(SuiteResponder::SUITE_SETUP_NAME, '-setup');
    }
    private function includeSetupPage(): void
    {
        $this->include('SetUp', '-setup');
    }
    private function includePageContent(): void
    {
        $this->newPageContent .= $this->pageData->getContent();
    }
    private function includeTeardownPages(): void
    {
        $this->includeTeardownPage();
        if ($this->isSuite) {
            $this->includeSuiteTeardownPage();
        }
    }
    private function includeTeardownPage(): void
    {
        $this->include('TearDown', '-teardown');
    }
    private function includeSuiteTeardownPage(): void
    {
        $this->include(SuiteResponder::SUITE_SETUP_NAME, '-teardown');
    }
    private function updatePageContent(): void
    {
        $this->pageData->setContent($this->newPageContent);
    }
    private function include(string $pageName, string $arg): void
    {
        $inheritedPage = $this->findInheritedPage($pageName);
        if($inheritedPage !== null){
            $pagePathName = $this->getPathNameForPage($inheritedPage);
            $this->buildIncludeDirective($pagePathName, $arg);

        }
    }
    private function findInheritedPage(string $pageName): ?WikiPage
    {
        return PageCrawlerImpl::getInheritedPage($pageName, $this->testPage);
    }
    private function getPathNameForPage(WikiPage $page): string{
        $pagePath = $this->pageCrawler->getFullPath($page);
        return PathParser::render($pagePath);
    }
    private function buildIncludeDirective(string $pagePathName, string $arg): void
    {
        $this->newPageContent = '\n!include '.$arg.' .'.$pagePathName.'\n';
    }

}



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

class WikiPage {
    public function getPageCrawler(): PageCrawler
    {
        return new PageCrawler();
    }
}
class PageCrawler {
    public function getFullPath(WikiPage $page): string
    {
        return '/var/www/html/path/page';
    }
}

class PageCrawlerImpl
{
    public static function getInheritedPage(string $pageName, WikiPage $testPage): ?WikiPage
    {
        return new WikiPage();
    }
}

class PathParser
{
    public static function render(string $pagePath): string
    {
        return $pagePath;
    }
}
class SuiteResponder
{
    const SUITE_SETUP_NAME = 'SuiteSetUp';
}