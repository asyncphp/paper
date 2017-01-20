<?php

namespace AsyncPHP\Paper\Driver;

use AsyncPHP\Paper\Driver;

abstract class BaseDriver implements Driver
{
    use Traits\AppendsTrait;
    use Traits\AppendsHeaderTrait;
    use Traits\AppendsFooterTrait;

    /**
     * @var string
     */
    protected $header;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var string
     */
    protected $footer;

    /**
     * @var string
     */
    protected $size = "A4";

    /**
     * @var string
     */
    protected $orientation = "portrait";

    /**
     * @var int
     */
    protected $dpi = 300;

    /**
     * @inheritdoc
     *
     * @param null|string $header
     *
     * @return string|static
     */
    public function header($header = null)
    {
        return $this->access("header", $header);
    }

    /**
     * Works as a universal getter and setter.
     *
     * @param string $key
     * @param mixed $value
     */
    private function access($key, $value = null)
    {
        if (is_null($value)) {
            return $this->$key;
        }

        $this->$key = $value;
        return $this;
    }

    /**
     * @inheritdoc
     *
     * @param null|string $body
     *
     * @return string|static
     */
    public function body($body = null)
    {
        return $this->access("body", $body);
    }

    /**
     * @inheritdoc
     *
     * @param null|string $footer
     *
     * @return string|static
     */
    public function footer($footer = null)
    {
        return $this->access("footer", $footer);
    }

    /**
     * @inheritdoc
     *
     * @param null|string $size
     *
     * @return string|static
     */
    public function size($size = null)
    {
        return $this->access("size", $size);
    }

    /**
     * @inheritdoc
     *
     * @param null|string $orientation
     *
     * @return string|static
     */
    public function orientation($orientation = null)
    {
        return $this->access("orientation", $orientation);
    }

    /**
     * @inheritdoc
     *
     * @param null|int $dpi
     *
     * @return int|static
     */
    public function dpi($dpi = null)
    {
        return $this->access("dpi", $dpi);
    }

    /**
     * Get context variables for parallel execution.
     *
     * @return array
     */
    protected function data()
    {
        $html = $this->appends(
            $this->body, "head", "<style>html,body{height:100%;position:relative;}</style>"
        );

        if ($this->header) {
            $html = $this->appendsHeader($html, $this->header);
        }

        if ($this->footer) {
            $html = $this->appendsFooter($html, $this->footer);
        }

        return [
            "header" => $this->header,
            "body" => $this->body,
            "footer" => $this->footer,
            "html" => $html,
            "size" => $this->size,
            "orientation" => $this->orientation,
            "dpi" => $this->dpi,
        ];
    }
}
