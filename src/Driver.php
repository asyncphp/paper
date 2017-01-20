<?php

namespace AsyncPHP\Paper;

interface Driver
{
    /**
     * Gets or sets the HTML body of the document.
     *
     * @param null|string $body
     *
     * @return string|static
     */
    public function body($body = null);

    /**
     * Gets or sets the page size of the document.
     *
     * @param null|string $size
     *
     * @return string|static
     */
    public function size($size = null);

    /**
     * Gets or sets the orientation of the document.
     *
     * @param null|string $orientation
     *
     * @return string|static
     */
    public function orientation($orientation = null);

    /**
     * Gets or sets the DPI of the document.
     *
     * @param null|int $dpi
     *
     * @return int|static
     */
    public function dpi($dpi = null);

    /**
     * Renders the document, returning an immediate or eventual string.
     *
     * @param Runner $runner
     *
     * @return mixed
     */
    public function render(Runner $runner);
}
