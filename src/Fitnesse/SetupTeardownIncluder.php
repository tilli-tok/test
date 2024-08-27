<?php
declare(strict_types=1);

namespace CleanCode\Fitnesse;
/**
fitnesse.html;
import fitnesse.responders.run.SuiteResponder;
import fitnesse.wiki.*;
*/
class SetupTeardownIncluder {
    private PageData $pageData;
    private bool $isSuite;
    private WikiPage $testPage;
    private StringBuffer $newPageContent;
    private PageCrawler $pageCrawler;


    public static function renderFromPageData(PageData $pageData): PageData
    {
        return self::renderFromPageDataSuite($pageData);
    }

    public static function renderFromPageDataSuite(PageData $pageData, bool $isSuite = false): string
    {
        return (new self($pageData))->render($isSuite);
    }

    private function SetupTeardownIncluder(PageData $pageData)
    {
        $this->pageData = $pageData;
        $this->testPage = $pageData->getWikiPage();
        $this->pageCrawler = $this->testPage->getPageCrawler();
        $this->newPageContent = new StringBuffer();
    }
    private function render(bool $isSuite): string
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
        $this->newPageContent->append($this->pageData->getContent());


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
        $this->pageData->setContent($this->newPageContent->toString());
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
    private function getPathNameForPage(WikiPage $page): string
    {
        $pagePath = $this->pageCrawler->getFullPath($page);
        return PathParser::renderFromPageData($pagePath);
    }
    private function buildIncludeDirective(string $pagePathName, string $arg): void
    {
        $this->newPageContent->append("\n!include $arg .$pagePathName\n");
    }
}