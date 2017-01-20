<?php

namespace AsyncPHP\Paper\Driver\Traits;

trait AppendsHeaderTrait
{
    /**
     * Appends a header (and corresponding styles), to an HTML document.
     *
     * @param string $document
     * @param string $header
     *
     * @return string
     */
    protected function appendsHeader($document, $header)
    {
        $document = $this->appends($document, "body", $this->headerHtml($header));
        $document = $this->appends($document, "head", $this->headerCss());

        return $document;
    }

    /**
     * Returns the HTML to be applied for the header.
     *
     * @param string $header
     *
     * @return string
     */
    protected function headerHtml($header)
    {
        return "<div class='paper-header'>{$header}</div>";
    }

    /**
     * Returns the CSS to be applied for the header.
     *
     * @return string
     */
    protected function headerCss()
    {
        return "<style>.paper-header{position:fixed;top:0;left:0;}</style>";
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
