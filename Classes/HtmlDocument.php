<?php
namespace MarvinMartian\HtmlMailClientRenderingSimulator;

/**
 * HtmlDocument for HTML Mail Client Rendering Simulator.
 *
 * @author Marvin-Martian https://github.com/marvin-martian
 * @copyright (c) 2015 Marvin Martian <marvin-martian@users.noreply.github.com>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class HtmlDocument extends Document
{

    const MimeType = "text/html";
    
    const Extension = "html";

    /**
     * CSS Document Collection
     *
     * @var CssDocumentCollection
     */
    protected $cssCollection;

    /**
     * PHP DomDocument of the HTML source
     *
     * @var \DOMDocument
     */
    protected $domDoc;

    /**
     * PHP DomXPath of the dom document
     *
     * @var \DOMXPath
     */
    protected $domXpath;

    /**
     * External CSS Request URI prefix.
     *
     * @var string
     */
    protected $externalCssRequestPrefix = './?cssFileHash=';

    /**
     * Parsed state
     * @var boolean
     */
    protected $isParsed = false;

    /**
     * Constructor
     *
     * @param string $href
     *            Document hypertext reference can be a HTML URL, a path to a HTML file
     *            or raw HTML source text.
     */
    
    /**
     * Constructor
     *
     * @param string $href
     *            Document hypertext reference can be a HTML URL, a path to a HTML file
     *            or raw HTML source text.
     * @param string $cachePath
     *            Path to writable cache directory. Defaults to {%SYSTEMP%}/HMCSimCache if possible.
     * @param string $externalCssRequestPrefix
     *            Prefix External CSS Request URI prefix. Defaults to "./?cssFileHash=".
     */
    public function __construct($href = '', $cachePath = null, $externalCssRequestPrefix = './?cssFileHash=' )
    {
        if (isset($externalCssRequestPrefix)) {
            $this->externalCssRequestPrefix = $externalCssRequestPrefix;
        }
        parent::__construct($href,$cachePath);
        $this->setDomDocument($this->src);
    }

    /**
     * Extracts the CSS from the document DOM.
     */
    protected function extractCssFromDom()
    {
        $this->cssCollection = new CssDocumentCollection();
        // First look for external css links
        $nodeList = $this->getXpath('//link[@rel="stylesheet" and @href]');
        /* @var $node \DOMElement */
        foreach ($nodeList as $node) {
            $this->cssCollection->addCssDocument(new CssDocument($node->getAttribute('href'), md5($node->getNodePath())));
        }
        // Look for style tags we treat these as CSS Documents as well.
        $nodeList = $this->getXpath('//style[contains(@media,"all") or contains(@media,"screen") or contains(@type,"text/css") or not(@*)]');
        foreach ($nodeList as $node) {
            $this->cssCollection->addCssDocument(new CssDocument($node->nodeValue, md5($node->getNodePath())));
        }
        // Look for inline style tags we treat these as CSS Documents as well.
        $nodeList = $this->getXpath('//*[@style]');
        foreach ($nodeList as $node) {
            // wrap it in a block so it gets parsed
            $this->cssCollection->addCssDocument(new CssDocument('__inline_style{' . $node->getAttribute('style') . '}', md5($node->getNodePath())));
        }
    }

    /**
     * Rewrites the extracted CSS back to the document DOM.
     */
    protected function writeCssToDom($format = '')
    {
        // Rewrite link tags
        $nodeList = $this->getXpath('//link[@rel="stylesheet" and @href]');
        /* @var $node \DOMElement */
        
        // do this backwards so we do not mess up the dom.
        for ($i = $nodeList->length; -- $i >= 0;) {
            $node = $nodeList->item($i);
            // Store external CSS to storage and set href to temp css file.
            if ($cssDocument = $this->cssCollection->getCssDocumentByDomId(md5($node->getNodePath()))) {
                if ($shaHash = $cssDocument->renderToStorage($format)) {
                    // Set the link request URI
                    $node->setAttribute('href',$this->externalCssRequestPrefix.$shaHash);
                }
            }
        }
        
        // Rewrite style tags
        $nodeList = $this->getXpath('//style[contains(@media,"all") or contains(@media,"screen") or contains(@type,"text/css") or not(@*)]');
        // do this backwards so we do not mess up the dom.
        
        for ($i = $nodeList->length; -- $i >= 0;) {
            $node = $nodeList->item($i);
            
            // Set style tag to render
            if ($cssDocument = $this->cssCollection->getCssDocumentByDomId(md5($node->getNodePath()))) {
                
                $node->nodeValue = $cssDocument->render();
            }
        }
        // Rewrite inline style attributes
        $nodeList = $this->getXpath('//*[@style]');
        for ($i = $nodeList->length; -- $i >= 0;) {
            $node = $nodeList->item($i);
            // Set style attribute to render
            if ($cssDocument = $this->cssCollection->getCssDocumentByDomId(md5($node->getNodePath()))) {
                // Remove faux parse block
                $node->setAttribute('style', rtrim(substr($cssDocument->render(), strlen('__inline_style{')), '}'));
            }
        }
    }

    /**
     * Setter for domDoc
     *
     * @param string $htmlSrc            
     * @throws Exception
     */
    protected function setDomDocument($htmlSrc)
    {
        $this->domDoc = new \DOMDocument();
        // libxml_use_internal_errors(true);
        $this->domDoc->preserveWhiteSpace = false;
        if (! $this->domDoc->loadHTML($htmlSrc)) {
            throw new \Exception('Could not parse HTML source as \DOMDocument');
        } else {
            // libxml_clear_errors();
            $this->setDomXpath($this->domDoc);
            $this->extractCssFromDom();
        }
    }

    /**
     * Setter for domXpath
     *
     * @param \DOMDocument $domDoc            
     */
    protected function setDomXpath(\DOMDocument $domDoc)
    {
        $this->domXpath = new \DOMXPath($domDoc);
    }

    /**
     * Removes CSS property
     *
     * @param string $property
     *            Property Name
     */
    public function removeCssProperty($property)
    {
        $this->cssCollection->removeCssProperty($property);
    }

    /**
     * Removes CSS property by value match
     *
     * @param string $match            
     * @param boolean $regExpr
     *            Whether or not to treat the match as a regular expression. Default: FALSE
     */
    public function removeCssPropertyByValueMatch($match, $regExpr = false)
    {
        $this->cssCollection->removeCssPropertyByValueMatch($match, $regExpr);
    }

    /**
     * removes CSS selector
     *
     * @param string $selector            
     * @param string $regExpr
     *            Whether or not to treat the selector as a regular expression. Default: FALSE
     */
    public function removeCssSelector($selector,$regExpr = false)
    {
        $this->cssCollection->removeCssSelector($selector,$regExpr);
    }

    /**
     * Removes HTML Tag
     *
     * @param string $tagName
     *            Tag Name
     */
    public function removeHtmlTag($tagName)
    {
        $this->removeHtmlTagByXpath('//' . strtolower($tagName));
    }

    /**
     * Removes HTML Tag by X-Path
     *
     * @param string $xPath
     *            X-Path
     */
    public function removeHtmlTagByXpath($xPath)
    {
        $nodeList = $this->getXpath($xPath);
        // do this backwards so we do not mess up the dom.
        for ($i = $nodeList->length; -- $i >= 0;) {
            $node = $nodeList->item($i);
            $node->parentNode->removeChild($node);
        }
    }

    /**
     * Get X-Path from domDoc
     *
     * @param string $xPath
     *            X-Path
     * @return DOMNodeList
     */
    protected function getXpath($xPath)
    {
        return $this->domXpath->query($xPath);
    }
    
    /**
     * Parses the HTML document with the supplied Mail Client.
     * @param MailClient $mailClient
     */
    public function parseMailClient(MailClient $mailClient)
    {
        $mailClient->parseHtmlDocument($this);
        $this->isParsed = true;
    }
    
    /**
     * Renders the parsed HTML as a string.
     * - override
     *
     * @param string|MailClient $format            
     * @return string
     */
    public function render($format = '')
    {
        if ($this->isParsed && $format !== 'src') {
            // Rerender CSS
            $this->writeCssToDom($format);
            $render = $this->domDoc->saveHTML();
        }
        if (!isset($render)) {
            // Render the raw source
            $render = parent::render($format);
        }
        $this->fileHash = sha1($render);
        return $render;
    }
}