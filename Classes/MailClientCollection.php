<?php
namespace MarvinMartian\HtmlMailClientRenderingSimulator;

/**
 * MailClientCollection for HTML Mail Client Rendering Simulator.
 * @author Marvin-Martian https://github.com/marvin-martian
 * @copyright (c) 2015 Marvin Martian <marvin-martian@users.noreply.github.com>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class MailClientCollection
{

    /**
     * An array of MailClient objects.
     *
     * @var array
     */
    protected $mailClients = array();

    /**
     * A hashmap of Mail Client ids.
     *
     * @var array
     */
    protected $hashMapMailClientId = array();

    /**
     * Constructor
     */
    public function __construct()
    {}

    /**
     * Adds a mail client to the collection.
     *
     * @param MailClient $mailClient            
     */
    public function addMailClient(MailClient $mailClient)
    {
        $index = count($this->mailClients);
        $this->mailClients[$index] = $mailClient;
        $this->hashMapMailClientId[$mailClient->getId()] = $index;
    }

    /**
     * Gets an array of MailClient objects by reference.
     *
     * @return array
     */
    public function &getMailClients()
    {
        return $this->mailClients;
    }

    /**
     * Gets a MailClient object by id.
     *
     * @param string $mailClientId            
     * @return MailClient|boolean Returns a MailClient object by reference or false if mail client not found.
     */
    public function getMailClientById($mailClientId)
    {
        if (isset($this->hashMapMailClientId[$mailClientId])) {
            return $this->getMailClientByIndex($this->hashMapMailClientId[$mailClientId]);
        }
        return false;
    }

    /**
     * Gets a MailClient object by index.
     *
     * @param integer $index            
     * @return MailClient|boolean Returns a MailClient object by reference or false if mail client not found.
     */
    public function &getMailClientByIndex($index)
    {
        if (isset($this->mailClients[$index]) && ($this->mailClients[$index] instanceof MailClient)) {
            return $this->mailClients[$index];
        }
        return false;
    }

    /**
     * Returns JS object array hash map.
     * 
     * @return string
     */
    public function getMailClientJSHashMap()
    {
        static $hashMap;
        if (isset($hashMap)) {
            return $hashMap;
        }
        $hashMap = '{';
        /* @var $mailClient MailClient  */
        foreach ($this->mailClients as $mailClient) {
            $hashMap .= '"'.$mailClient->getId() . '":[';
            /* @var $condition MailClientCondition  */
            foreach ($mailClient->getConditions() as $condition) {
                $hashMap .= '"' . $condition->getId() . '",';
            }
            $hashMap = rtrim($hashMap, ',');
            $hashMap .= '],';
        }
        $hashMap = rtrim($hashMap, ',');
        $hashMap .= '};';
        return $hashMap;
    }

    /**
     * Returns HTML select options of available Mail Clients
     *
     * @return string
     */
    public function getMailClientSelectOptions()
    {
        static $selectOptions;
        
        if (isset($selectOptions)) {
            return $selectOptions;
        }
        $optionsArray = array();
        $mailClients = $this->getMailClients();
        foreach ($mailClients as $mailClient) {
            if ($mailClient instanceof MailClient) {
                if (! isset($optionsArray[$mailClient->getPlatform()])) {
                    $optionsArray[$mailClient->getPlatform()] = array();
                }
                $optionsArray[$mailClient->getPlatform()][] = array(
                    'value' => $mailClient->getId(),
                    'name' => $mailClient->getName()
                );
            }
        }
        $selectOptions = '';
        foreach ($optionsArray as $optGrpLabel => $options) {
            $selectOptions .= '<optgroup label="' . htmlspecialchars($optGrpLabel) . '">';
            foreach ($options as $option) {
                $selectOptions .= '<option value="' . htmlspecialchars($option['value']) . '">' . htmlspecialchars($option['name']) . '</option>';
            }
            $selectOptions .= '</optgroup>';
        }
        return $selectOptions;
    }
}