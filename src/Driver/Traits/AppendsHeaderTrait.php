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
        $document = $this->appends(
            $document, "head",
            "<style>.paper-header{position:fixed;top:0;left:0;width:100%;}</style>"
        );

        $document = $this->appends($document, "body", "<div class='paper-header'>{$header}</div>");

        return $document;
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
