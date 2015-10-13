<?php
namespace MarvinMartian\HtmlMailClientRenderingSimulator;

/**
 * RenderingCondition for HTML Mail Client Rendering Simulator.
 *
 * @author Marvin-Martian https://github.com/marvin-martian
 * @copyright (c) 2015 Marvin Martian <marvin-martian@users.noreply.github.com>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class HtmlMailClientRenderingSimulator
{

    /**
     * HTML Document object
     *
     * @var HtmlDocument
     */
    protected $htmlDoc;

    /**
     * File path to the Campaign Monitor Guide to CSS XLSX
     *
     * @see https://www.campaignmonitor.com/css/
     * @var string
     */
    protected $filePathToCampaignMonitorExcel;

    /**
     * Mail client collection
     *
     * @var MailClientCollection
     */
    protected $mailClientCollection;

    /**
     * External CSS Request URI prefix.
     *
     * @var string
     */
    protected $externalCssRequestPrefix = './?cssFileHash=';

    /**
     * Path to writable temp folder.
     *
     * @var string
     */
    protected $cachePath;

    /**
     * Constructor
     *
     * @param string $htmlSrc
     *            Document HTML Source can be a HTML URL, a path to a HTML file or raw HTML source text.
     * @param string $externalCssRequestPrefix
     *            Prefix External CSS Request URI prefix. Defaults to "./?renderCss=".
     * @param string $cachePath
     *            Path to a writable temp folder. Defaults to system temp if possible.
     * @throws \ErrorException
     */
    public function __construct($htmlSrc = '', $externalCssRequestPrefix = './?cssFileHash=', $cachePath = null)
    {
        if (isset($externalCssRequestPrefix)) {
            $this->externalCssRequestPrefix = $externalCssRequestPrefix;
        }
        if (is_null($cachePath)) {
            $cachePath = Document::getSysCacheDir();
        }
        if (! Document::isWritable($cachePath)) {
            throw new \ErrorException('Need a writable temporary storage directory, please check the permissions of this directory:' . $cachePath);
        }
        $this->cachePath = $cachePath;
        $this->init();
        if (isset($htmlSrc)) {
            $this->htmlDoc = new HtmlDocument($htmlSrc, $this->cachePath, $this->externalCssRequestPrefix);
        }
    }

    /**
     * Inits the base variables.
     */
    protected function init()
    {
        if (! isset($this->filePathToCampaignMonitorExcel)) {
            $this->filePathToCampaignMonitorExcel = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'Private' . DIRECTORY_SEPARATOR . 'Guide to CSS Support in Email.xlsx';
        }
        if (! isset($this->mailClientCollection)) {
            $this->mailClientCollection = $this->parseCampaignMonitorExcel();
        }
    }

    /**
     * Getter for mail client collection.
     *
     * @return \MarvinMartian\HtmlMailClientRenderingSimulator\MailClientCollection
     */
    public function &getMailClientCollection()
    {
        return $this->mailClientCollection;
    }

    /**
     * Renders the HTML document.
     *
     * @param string $format            
     * @return string Returns HTML source
     */
    public function renderHtml($format = '')
    {
        return $this->htmlDoc->render($format);
    }

    /**
     * Outputs the HTML document to browser.
     */
    public function outputHtml($format = '')
    {
        $this->htmlDoc->output($format);
    }

    /**
     * Parses the HTML document by MailClient object
     *
     * @param MailClient $mailClient            
     */
    public function parseHtmlByMailClient(MailClient $mailClient)
    {
        $this->htmlDoc->parseMailClient($mailClient);
    }

    /**
     * Parses the HTML document by MailClient Id
     *
     * @param string $mailClientId            
     * @return string Returns HTML source
     */
    public function parseHtmlByMailClientId($mailClientId)
    {
        if ($mailClient = $this->mailClientCollection->getMailClientById($mailClientId)) {
            $this->parseHtmlByMailClient($this->mailClientCollection->getMailClientById($mailClientId));
        }
    }

    /**
     * Renders the external css identified by SHA hash
     *
     * @param string $shaHash            
     * @return string
     */
    public function renderExternalCss($shaHash)
    {
        return file_get_contents($this->getCssFilePathByShaHash($shaHash));
    }

    /**
     * Gets the path to the external CSS file identified by SHA hash.
     *
     * @param string $shaHash            
     * @return string
     */
    protected function getCssFilePathByShaHash($shaHash)
    {
        $cssFileName = $shaHash . '.css';
        return rtrim($this->cachePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $cssFileName;
    }

    /**
     * Outputs the CSS to browser identified by SHA hash
     *
     * @param string $shaHash            
     */
    public function outputExternalCSS($shaHash)
    {}

    /**
     * Retrieves the stored mail client collection object.
     *
     * @param string $pathToMailClientCollection            
     * @return MailClientCollection|boolean Returns false on failure.
     */
    private function getStoredMailClientCollection($pathToMailClientCollection)
    {
        if (file_exists($pathToMailClientCollection)) {
            $serializedObj = file_get_contents($pathToMailClientCollection);
            $serializedObj = gzuncompress($serializedObj);
            $mailClientCollection = unserialize($serializedObj);
            if ($mailClientCollection instanceof MailClientCollection) {
                return $mailClientCollection;
            }
        }
        return false;
    }

    /**
     * Parses the excel file from https://www.campaignmonitor.com/css/ for newsletter CSS rules.
     *
     * @see https://www.campaignmonitor.com/css/
     * @param string $filePathToCampaignMonitorExcel
     *            The file path to the Campaign Monitor Excel file.
     * @return MailClientCollection
     */
    public function parseCampaignMonitorExcel($filePathToCampaignMonitorExcel = '')
    {
        static $mailClientCollection;
        
        // Check if we already have a collection.
        if ($mailClientCollection instanceof MailClientCollection) {
            return $mailClientCollection;
        }
        
        if ($filePathToCampaignMonitorExcel !== '') {
            $this->filePathToCampaignMonitorExcel = $filePathToCampaignMonitorExcel;
        }
        
        // Check for a cached mail client collection.
        $fileName = 'MailClientCollection_' . md5_file($this->filePathToCampaignMonitorExcel) . '.tmp.gz';
        
        // Look in private resources first
        $tmpDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'Private';
        $tmpFile = $tmpDir . DIRECTORY_SEPARATOR . $fileName;
        if ($mailClientCollection = $this->getStoredMailClientCollection($tmpFile)) {
            return $mailClientCollection;
        }
        
        // Look in temp directory
        $tmpDir = $this->cachePath;
        $tmpDir = rtrim($tmpDir, DIRECTORY_SEPARATOR);
        $tmpFile = $tmpDir . DIRECTORY_SEPARATOR . $fileName;
        if ($mailClientCollection = $this->getStoredMailClientCollection($tmpFile)) {
            return $mailClientCollection;
        }
        
        // Ok Proceed with parsing the excel.
        $objPHPExcel = \PHPExcel_IOFactory::load($this->filePathToCampaignMonitorExcel);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
        $mailClientCollection = new MailClientCollection();
        $elementGroup = '';
        $conditions = array();
        foreach ($sheetData as $rowIndex => &$row) {
            
            switch ($rowIndex) {
                case 1:
                    // First Row is ClientPlatform
                    // Client platform is spanned so set each spanned column as platform.
                    $currentPlatform = '';
                    foreach ($row as $colKey => &$col) {
                        $col = trim($col);
                        if ($col !== '') {
                            $currentPlatform = $col;
                        }
                        $col = $currentPlatform;
                    }
                    break;
                case 3:
                    // Third Row is Client
                    $colIndex = 1;
                    foreach ($row as $colKey => &$col) {
                        $col = trim($col);
                        if ($col !== '' && $colIndex >= 4) {
                            $mailClientCollection->addMailClient(new MailClient((string) $col, (string) $sheetData[1][$colKey], $this->cachePath));
                        }
                        $colIndex ++;
                    }
                    break;
                default:
                    $filteredRow = array_filter($row);
                    $filteredRowCount = count($filteredRow);
                    switch ($filteredRowCount) {
                        case 0:
                            // This is an empty row.. ignore
                            break;
                        case 1:
                            // This is a elementGroup
                            $elementGroup = trim($filteredRow['A']);
                            break;
                        default:
                            // This is a element.
                            $colIndex = 1;
                            $clientIndex = 0;
                            $element = '';
                            foreach ($row as $colKey => &$col) {
                                $col = trim($col);
                                if ($colIndex == 1) {
                                    $element = (string) $col;
                                }
                                if ($sheetData[3][$colKey] !== '' && $colIndex >= 4 && $rowIndex > 3 && $elementGroup !== '') {
                                    
                                    if ((strtolower($col) !== 'yes')) {
                                        if ($mailClient = $mailClientCollection->getMailClientByIndex($clientIndex)) {
                                            $mailClient->addMailClientCondition($element, $elementGroup);
                                        }
                                    }
                                    // $cHash = substr(md5($element . $elementGroup), 0, 8);
                                    // if (!isset($conditions[$cHash])) {
                                    // $conditions[$cHash] = array($element, $elementGroup);
                                    // }
                                    
                                    $clientIndex ++;
                                }
                                $colIndex ++;
                            }
                            break;
                    }
                    break;
            }
        }
        // Cache it for next time.
        $serializedObj = gzcompress(serialize($mailClientCollection), 9);
        file_put_contents($tmpFile, $serializedObj);
        //
        // $idx = 1;
        // $php = "<?php\n";
        // foreach ($conditions as $key=>$value) {
        // $php .= "\n";
        // $php .= "/**\n";
        // $php .= " * [$key] Test-$idx: " . $value[1]." - ".$value[0]."\n";
        // // $php .= " * @param HtmlDocument \$htmlDoc\n";
        // $php .= " * @return HtmlDocument\n";
        // $php .= " */\n";
        // $php .= "public function cond_".$key."(HtmlDocument \$htmlDoc)\n";
        // $php .= "{\n";
        // $php .= "\$htmlDoc->removeCssProperty('".$value[0]."');\n";
        // $php .= "return \$htmlDoc;\n";
        // $php .= "}\n";
        // $idx++;
        // }
        // file_put_contents(__DIR__.DIRECTORY_SEPARATOR.'HtmlDocumentParser.php',$php);
        
        return $mailClientCollection;
    }
}