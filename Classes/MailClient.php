<?php
namespace MarvinMartian\HtmlMailClientRenderingSimulator;

/**
 * MailClient for HTML Mail Client Rendering Simulator.
 * @author Marvin-Martian https://github.com/marvin-martian
 * @copyright (c) 2015 Marvin Martian <marvin-martian@users.noreply.github.com>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class MailClient
{

    /**
     * Mail client id
     *
     * @var string
     */
    protected $id = '';

    /**
     * Mail client name
     *
     * @var string
     */
    protected $name = '';

    /**
     * Mail client platform
     *
     * @var string
     */
    protected $platform = '';

    /**
     * Rendering conditions
     *
     * @var array
     */
    protected $conditions = array();

    /**
     * Constructor
     */
    public function __construct($name = '', $platform = '')
    {
        $this->name = $name;
        $this->platform = $platform;
        $this->id = substr(md5($name . $platform), 0, 8);
    }

    /**
     * Adds a mail client rendering condition.
     *
     * @param string $element            
     * @param string $elementGroup            
     */
    public function addMailClientCondition($element, $elementGroup)
    {
        $this->conditions[] = new MailClientCondition($element, $elementGroup);
    }

    /**
     * Getter for Email Client ID
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Getter for Email Client Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Getter for Email Client Platform
     *
     * @return string
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * Getter for Rendering Conditions
     *
     * @return array
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * Parse HtmlDocument according to Mail client conditions
     *
     * @param HtmlDocument $htmDocument            
     */
    public function parseHtmlDocument(HtmlDocument &$htmDocument)
    {
        /* @var $condition MailClientCondition */
        foreach ($this->conditions as $condition) {
            $method = array(
                __NAMESPACE__ . '\MailClientParser',
                'parseRule_' . $condition->getId()
            );
            if (is_callable($method)) {
                call_user_func_array($method,array(&$htmDocument));
            }
        }
    }
}