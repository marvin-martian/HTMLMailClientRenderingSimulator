<?php
namespace MarvinMartian\HtmlMailClientRenderingSimulator;

use Sabberworm\CSS\Parser;
use Sabberworm\CSS\OutputFormat;
use Sabberworm;

/**
 * CssDocument for HTML Mail Client Rendering Simulator.
 *
 * @author Marvin-Martian https://github.com/marvin-martian
 * @copyright (c) 2015 Marvin Martian <marvin-martian@users.noreply.github.com>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class CssDocument extends Document
{

    const MimeType = "text/css";

    const Extension = "css";

    /**
     * HTML Dom Id
     *
     * @var string
     */
    protected $domId;

    /**
     *
     * @var \Sabberworm\CSS\CSSList\Document
     */
    protected $cssObject;

    /**
     * Constructor
     *
     * @param string $href
     *            Document hypertext reference can be a CSS URL, a path to a CSS file
     *            or raw CSS source text. [REQUIRED]
     * @param string $domId
     *            The DomId of where the CSS came from. [OPTIONAL]
     * @param string $cachePath
     *            Path to writable cache directory. Defaults to {%SYSTEMP%}/HMCSimCache if possible.
     */
    public function __construct($href = '', $domId = '', $cachePath = null)
    {
        parent::__construct($href, $cachePath);
        if (isset($domId)) {
            $this->domId = $domId;
        }
        $cssParser = new Parser($this->src);
        $this->cssObject = $cssParser->parse();
    }

    /**
     * Getter for HTML Dom Id
     *
     * @return string
     */
    public function getDomId()
    {
        return $this->domId;
    }

    /**
     * Removes CSS property
     *
     * @param string $property
     *            Property Name
     */
    public function removeCssProperty($property)
    {
        $propertyVariations = array(
            $property,
            '-ms-' . $property,
            '-o-' . $property,
            '-moz-' . $property,
            '-webkit-' . $property,
            '_' . $property,
            '*' . $property
        );
        /* @var $ruleSet Sabberworm\CSS\RuleSet  */
        foreach ($this->cssObject->getAllRuleSets() as $ruleSet) {
            // Step through potential variations and hacks of property
            foreach ($propertyVariations as $cssProp) {
                $ruleSet->removeRule($cssProp);
            }
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
        /* @var $ruleSet Sabberworm\CSS\RuleSet  */
        foreach ($this->cssObject->getAllRuleSets() as $ruleSet) {
            /* @var $rule Sabberworm\CSS\Rule  */
            foreach ($ruleSet->getRules() as $rule) {
                $value = (string) $rule->getValue();
                if ($regExpr && preg_match($match, $value)) {
                    $ruleSet->removeRule($rule->getRule());
                    continue;
                }
                if (stripos($value, $match) !== false) {
                    $ruleSet->removeRule($rule->getRule());
                }
            }
        }
    }

    /**
     * Removes CSS selector
     *
     * @param string $selectorMatch            
     * @param string $regExpr
     *            Whether or not to treat the selector as a regular expression. Default: FALSE
     */
    public function removeCssSelector($selectorMatch, $regExpr = false)
    {
        foreach ($this->cssObject->getContents() as $cssList) {
            switch (true) {
                case ($cssList instanceof Sabberworm\CSS\CSSList\AtRuleBlockList):
                case ($cssList instanceof Sabberworm\CSS\Property\Import):
                case ($cssList instanceof Sabberworm\CSS\RuleSet\AtRuleSet):
                    if (strlen($selectorMatch) > 1 && substr($selectorMatch, 0, 1) == '@') {
                        if ($cssList->atRuleName() == ltrim($selectorMatch, '@')) {
                            $this->cssObject->remove($cssList);
                        }
                    }
                    break;
                // check for a declaration block
                case ($cssList instanceof Sabberworm\CSS\RuleSet\DeclarationBlock):
                    foreach ($cssList->getSelectors() as $selector) {
                        if ($selector->getSelector() == '__inline_style') {
                            // Ignore blocks of inline-styles as these get property treatment only
                            continue;
                        }
                        if ($regExpr && preg_match($selectorMatch, $selector->getSelector()) !== false) {
                            $cssList->removeSelector($selector);
                            continue;
                        }
                        if (stripos($selector->getSelector(), $selectorMatch) !== false) {
                            $this->cssObject->remove($cssList);
                        }
                    }
                    if (count($cssList->getSelectors()) == 0) {
                        $this->cssObject->remove($cssList);
                    }
                    break;
                default:
                // var_dump($cssList);
            }
        }
    }

    /**
     * Renders the parsed CSS as a string.
     * - override
     *
     * @param string|Sabberworm\CSS\OutputFormat $format
     *            The format can be as a string "compact"|"pretty" or your own custom Sabberworm\CSS\OutputFormat. Default: "compact"
     * @return string
     */
    public function render($format = '')
    {
        if (is_null($format) || empty($format)) {
            $format = 'compact';
        }
        if (is_string($format) && $format = strtolower($format)) {
            switch ($format) {
                case 'compact':
                    
                    $render = $this->cssObject->render(OutputFormat::createCompact());
                    break;
                default:
                    $render = $this->cssObject->render(OutputFormat::createPretty());
                    break;
            }
        }
        if (! isset($render) && $format instanceof OutputFormat) {
            $render = $this->cssObject->render($format);
        } elseif (! isset($render)) {
            $render = $this->cssObject->render();
        }
        $this->fileHash = sha1($render);
        return $render;
    }
}