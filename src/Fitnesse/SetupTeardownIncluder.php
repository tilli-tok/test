<?php
declare(strict_types=1);

namespace CleanCode\Fitnesse;

class SetupTeardownIncluder {
    private bool $isSuite;
    private WikiPage $testPage;
    private PageCrawler $pageCrawler;
    private PageData $pageData;
    private StringBuffer $newPageContent;

    private function __construct(PageData $pageData)
    {
        $this->pageData = $pageData;
        $this->testPage = $pageData->getWikiPage();
        $this->pageCrawler = $this->testPage->getPageCrawler();
        $this->newPageContent = new StringBuffer();
    }
    /*
     * @throws \Exception
     * */
    public static function renderFromPageData(PageData $pageData): string
    {
        return SetupTeardownIncluder::renderFromPageDataSuite($pageData);
    }
    /*
     * @throws Exception
     * */
    public static function renderFromPageDataSuite(PageData $pageData, bool $isSuite = false): string
    {
        $setupTeardown = new SetupTeardownIncluder($pageData);
        return ($setupTeardown)->render($isSuite);
    }
    private function render(bool $isSuite): string
    {
        $this->isSuite = $isSuite;
        if ($this->isTestPage()) {
            $this->includeSetupAndTeardownPages();
        }
        return $this->pageData->getHtml();
    }
    /*
     * @throws Exception
     * */
    private function isTestPage(): bool
    {
        return $this->pageData->hasAttribute('Test');
    }
    /*
     * @throws Exception
     * */
    private function includeSetupAndTeardownPages(): void
    {
        $this->includeSetupPages();
        $this->includePageContent();
        $this->includeTeardownPages();
        $this->updatePageContent();
    }
    /*
     * @throws Exception
     * */
    private function includeSetupPages(): void
    {
        if ($this->isSuite) {
            $this->includeSuiteSetupPage();
        }
        $this->includeSetupPage();
    }
    /*
     * @throws Exception
     * */
    private function includeSuiteSetupPage(): void
    {
        $this->include(SuiteResponder::SUITE_SETUP_NAME, '-setup');
    }
    /*
     * @throws Exception
     * */
    private function includeSetupPage(): void
    {
        $this->include('SetUp', '-setup');
    }
    /*
     * @throws Exception
     * */
    private function includePageContent(): void
    {
        $this->newPageContent->append($this->pageData->getContent());
    }
    /*
     * @throws Exception
     * */
    private function includeTeardownPages(): void
    {
        $this->includeTeardownPage();
        if ($this->isSuite) {
            $this->includeSuiteTeardownPage();
        }
    }
    /*
     * @throws Exception
     * */
    private function includeTeardownPage(): void
    {
        $this->include('TearDown', '-teardown');
    }
    /*
     * @throws Exception
     * */
    private function includeSuiteTeardownPage(): void
    {
        $this->include(SuiteResponder::SUITE_SETUP_NAME, '-teardown');
    }
    /*
     * @throws Exception
     * */
    private function updatePageContent(): void
    {
        $this->pageData->setContent($this->newPageContent->__toString());
    }
    /*
     * @throws Exception
     * */
    private function include(string $pageName, string $arg): void
    {
        $inheritedPage = $this->findInheritedPage($pageName);
        if($inheritedPage !== null){
            $pagePathName = $this->getPathNameForPage($inheritedPage);
            $this->buildIncludeDirective($pagePathName, $arg);

        }
    }
    /*
     * @throws Exception
     * */
    private function findInheritedPage(string $pageName): ?WikiPage
    {
        return PageCrawlerImpl::getInheritedPage($pageName, $this->testPage);
    }
    /*
     * @throws Exception
     * */
    private function getPathNameForPage(WikiPage $page): string
    {
        $pagePath = $this->pageCrawler->getFullPath($page);
        return PathParser::renderFromPageData($pagePath);
    }
    private function buildIncludeDirective(string $pagePathName, string $arg): void
    {
        $this->newPageContent
            ->append("\n!include ")
            ->append($arg)
            ->append(" .")
            ->append($pagePathName)
            ->append("\n");
    }
}