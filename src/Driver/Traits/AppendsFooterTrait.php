<?php

namespace AsyncPHP\Paper\Driver\Traits;

trait AppendsFooterTrait
{
    /**
     * Appends a footer (and corresponding styles), to an HTML document.
     *
     * @param string $document
     * @param string $footer
     *
     * @return string
     */
    protected function appendsFooter($document, $footer)
    {
        $document = $this->appends($document, "body", $this->footerHtml($footer));
        $document = $this->appends($document, "head", $this->footerCss());

        return $document;
    }

    /**
     * Returns the HTML to be applied for the footer.
     *
     * @param string $footer
     *
     * @return string
     */
    protected function footerHtml($footer)
    {
        return "<div class='paper-footer'>{$footer}</div>";
    }

    /**
     * Returns the CSS to be applied for the footer.
     *
     * @return string
     */
    protected function footerCss()
    {
        return "<style>.paper-footer{position:fixed;bottom:0;left:0;}</style>";
    }

    /**
     * Appends some HTML to an element, selected out of an HTML string.
     *
     * @param string $document
     * @param string $selector
     * @param string $content
     *
     * @return string
     */
    protected abstract function appends($document, $selector, $content);
}
