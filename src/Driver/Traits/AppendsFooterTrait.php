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
    protected function appendsHeader($document, $footer)
    {
        $document = $this->appends($document, "head", "
            <style>
                .paper-footer {
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    width: 100%;
                }
            </style>
        ");

        $document = $this->appends($document, "body", "<div class='paper-footer'>{$footer}</div>");

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
