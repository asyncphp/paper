<?php

namespace AsyncPHP\Paper\Driver\Traits;

use DOMDocument;
use DOMNode;
use Symfony\Component\DomCrawler\Crawler;

trait AppendsTrait
{
    /**
     * Appends some HTML to an element, selected out of an HTML string.
     *
     * @param string $document
     * @param string $selector
     * @param string $content
     *
     * @return string
     */
    protected function appends($document, $selector, $content)
    {
        $crawler = new Crawler($document);

        $crawler->filter($selector)->each(function($crawler, $i) use ($content) {
            $node = $crawler->getNode(0);

            $fragment = $node->ownerDocument->createDocumentFragment();
            $fragment->appendXML($content);

            $imported = $node->ownerDocument->importNode($fragment);
            $node->appendChild($imported);
        });

        $html = $crawler->getNode(0);
        return $html->ownerDocument->saveHTML($html);
    }
}
