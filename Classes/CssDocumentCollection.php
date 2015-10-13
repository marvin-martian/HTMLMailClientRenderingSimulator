<?php
namespace MarvinMartian\HtmlMailClientRenderingSimulator;

/**
 * CssDocumentCollection for HTML Mail Client Rendering Simulator.
 *
 * @author Marvin-Martian https://github.com/marvin-martian
 * @copyright (c) 2015 Marvin Martian <marvin-martian@users.noreply.github.com>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class CssDocumentCollection
{

    /**
     * An array of Css Document objects.
     *
     * @var array
     */
    protected $cssDocuments = array();

    /**
     * A hashmap of Css Document Ids.
     *
     * @var array
     */
    protected $hashMapCssDocId = array();

    /**
     * A hashmap of Css DOM Ids.
     *
     * @var array
     */
    protected $hashMapCssDomId = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cssDocuments = array();
    }

    /**
     * Adds a CSS document to the collection.
     *
     * @param CssDocument $cssDocument            
     */
    public function addCssDocument(CssDocument $document)
    {
        $index = count($this->cssDocuments);
        $this->cssDocuments[$index] = $document;
        $this->hashMapCssDocId[$document->getId()] = $index;
        if ($domId = $document->getDomId()) {
            $this->hashMapCssDomId[$domId] = $index;
        }
    }

    /**
     * Gets a CssDocument object by id.
     *
     * @param string $cssDocumentId            
     * @return CssDocument|boolean Returns a CssDocument object by reference or false if document not found.
     */
    public function getCssDocumentById($cssDocumentId)
    {
        return isset($this->hashMapCssDocId[$cssDocumentId]) ? $this->getCssDocumentByIndex($this->hashMapCssDocId[$cssDocumentId]) : false;
    }

    /**
     * Gets a CssDocument object by HTML DOM id.
     *
     * @param string $htmlDomId            
     * @return CssDocument|boolean Returns a CssDocument object by reference or false if document not found.
     */
    public function getCssDocumentByDomId($htmlDomId)
    {
        return isset($this->hashMapCssDomId[$htmlDomId]) ? $this->getCssDocumentByIndex($this->hashMapCssDomId[$htmlDomId]) : false;
    }

    /**
     * Gets a CssDocument object by index.
     *
     * @param integer $index            
     * @return CssDocument|boolean Returns a CssDocument object by reference or false if document not found.
     */
    public function &getCssDocumentByIndex($index)
    {
        if (isset($this->cssDocuments[$index]) && ($this->cssDocuments[$index] instanceof CssDocument)) {
            return $this->cssDocuments[$index];
        }
        return false;
    }

    /**
     * Gets a CssDocument object by file hash.
     *
     * @param string $hash            
     * @return CssDocument|boolean Returns a CssDocument object by reference or false if document not found.
     */
    public function &getCssDocumentByFileHash($hash)
    {
        static $hashMapCssFileHash;
        if (! isset($hashMapCssFileHash)) {
            $hashMapCssFileHash = array();
        }
        if (isset($hashMapCssFileHash[$hash])) {
            return $this->getCssDocumentByIndex($hashMapCssFileHash[$hash]);
        }
        // Loop through jump out when you can.
        /* @var $cssDocument CssDocument */
        foreach ($this->cssDocuments as $index => &$cssDocument) {
            $cssDocumentHash = $cssDocument->getFileHash();
            if (! is_null($cssDocumentHash) && ! isset($hashMapCssFileHash[$cssDocumentHash])) {
                $hashMapCssFileHash[$cssDocumentHash] = $index;
            }
            if ($hash == $cssDocumentHash) {
                return $this->getCssDocumentByIndex($hashMapCssFileHash[$hash]);
            }
        }
        return false;
    }

    /**
     * Removes CSS property in collection
     *
     * @param string $property
     *            Property Name
     */
    public function removeCssProperty($property)
    {
        /* @var $cssDocument CssDocument */
        foreach ($this->cssDocuments as &$cssDocument) {
            $cssDocument->removeCssProperty($property);
        }
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
        /* @var $cssDocument CssDocument */
        foreach ($this->cssDocuments as &$cssDocument) {
            $cssDocument->removeCssPropertyByValueMatch($match, $regExpr);
        }
    }

    /**
     * Removes CSS selector in collection
     *
     * @param string $selector            
     * @param string $regExpr
     *            Whether or not to treat the selector as a regular expression. Default: FALSE
     */
    public function removeCssSelector($selector, $regExpr = false)
    {
        /* @var $cssDocument CssDocument */
        foreach ($this->cssDocuments as &$cssDocument) {
            $cssDocument->removeCssSelector($selector, $regExpr);
        }
    }
}