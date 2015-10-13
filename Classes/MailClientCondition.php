<?php
namespace MarvinMartian\HtmlMailClientRenderingSimulator;

/**
 * MailClientCondition for HTML Mail Client Rendering Simulator.
 * @author Marvin-Martian https://github.com/marvin-martian
 * @copyright (c) 2015 Marvin Martian <marvin-martian@users.noreply.github.com>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class MailClientCondition
{

    /**
     * Condition name
     *
     * @var string
     */
    protected $name = '';

    /**
     * Condition group
     *
     * @var string
     */
    protected $group = '';

    /**
     * Condition Id
     *
     * @var string
     */
    protected $id = '';

    /**
     * Constructor
     */
    public function __construct($name = '', $group = '')
    {
        $this->name = $name;
        $this->group = $group;
        $this->id = substr(md5($name . $group), 0, 8);
    }

    /**
     * Getter for Name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Getter for Id
     * 
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}