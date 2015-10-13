<?php
namespace MarvinMartian\HtmlMailClientRenderingSimulator;

/**
 * HtmlDocument for HTML Mail Client Rendering Simulator.
 *
 * @author Marvin-Martian https://github.com/marvin-martian
 * @copyright (c) 2015 Marvin Martian <marvin-martian@users.noreply.github.com>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Document
{

    /**
     * Document Id
     *
     * @var string
     */
    protected $id;

    /**
     * Document hypertext reference
     *
     * @var string
     */
    protected $href;

    /**
     * Document source
     *
     * @var string
     */
    protected $src;

    /**
     * Is Document Url
     *
     * @var boolean
     */
    protected $isUrl = false;

    /**
     * Is Document File
     *
     * @var boolean
     */
    protected $isFile = false;

    /**
     * Is Raw Source
     *
     * @var boolean
     */
    protected $isRawSrc = false;

    /**
     * Path to writable temp folder.
     *
     * @var string
     */
    protected $cachePath;

    /**
     * Current hash of content.
     *
     * @var string
     */
    protected $fileHash;

    /**
     * Constructor
     *
     * @param string $href
     *            Document hypertext reference can be a URL, a filepath or raw source. [REQUIRED]
     * @param string $cachePath
     *            Path to writable cache directory. Defaults to {%SYSTEMP%}/HMCSimCache if possible.
     * @throws \ErrorException
     */
    public function __construct($href, $cachePath = null)
    {
        $this->setHref($href);
        if (is_null($cachePath)) {
            $cachePath = $this->getSysCacheDir();
        }
        if (! $this->isWritable($cachePath)) {
            throw new \ErrorException('Need a writable temporary storage directory, please check the permissions of this directory:' . $cachePath);
        }
        $this->cachePath = $cachePath;
    }

    /**
     * Returns the system cache directory.
     *
     * @throws \ErrorException
     * @return string
     */
    public static function getSysCacheDir()
    {
        // Try and get it.
        if (! ($cachePath = @sys_get_temp_dir())) {
            throw new \ErrorException('Need a temporary storage directory.');
        } else {
            // create a folder that can be easily deleted.
            $cachePath = rtrim($cachePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'HMCSimCache';
            if (! is_dir($cachePath) && ! mkdir($cachePath, 0775)) {
                throw new \ErrorException('Could not create a temporary storage directory, please check the permissions of this directory path:' . $cachePath);
            }
        }
        return $cachePath;
    }

    /**
     * Checks if directory is writable.
     *
     * @param string $path            
     * @return boolean
     */
    public static function isWritable($path)
    {
        global $HMCS_WritableHashTable;
        $pathHash = sha1($path);
        if (! isset($HMCS_WritableHashTable)) {
            $HMCS_WritableHashTable = array();
        }
        if (isset($HMCS_WritableHashTable[$pathHash])) {
            return $HMCS_WritableHashTable[$pathHash];
        }
        $writable = false;
        if (is_dir($path) && $tmpFile = tempnam($path, '_tmp')) {
            $writable = ! (stristr($tmpFile, $path) === false);
            @unlink($tmpFile);
        }
        $HMCS_WritableHashTable[$pathHash] = $writable;
        return $HMCS_WritableHashTable[$pathHash];
    }

    /**
     * init
     */
    protected function init()
    {
        $this->href = NULL;
        $this->src = NULL;
        $this->id = NULL;
        $this->isUrl = false;
        $this->isFile = false;
        $this->isRawSrc = false;
    }

    /**
     * Getter for Document Id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Getter for Hypertext Reference
     *
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * Getter for fileHash.
     * The file hash only exists if the document has been rendered.
     *
     * @return string
     */
    public function getFileHash()
    {
        return $this->fileHash;
    }

    /**
     * Setter for Hypertext Reference
     *
     * @param string $href
     *            Hypertext reference of the document can be a URL a filepath or raw source. [REQUIRED]
     * @throws \Exception
     */
    public function setHref($href)
    {
        $this->init();
        if (isset($href)) {
            $this->href = $href;
            $this->src = $this->resolveDocumentSource($this->href);
            $this->id = md5($this->href);
        } else {
            throw new \Exception('Need a vaild HREF for Document');
        }
    }

    /**
     * Sets the reference type of the hypertext reference
     *
     * @param string $href            
     */
    private function setHrefType($href)
    {
        $lineCount = substr_count($href, "\n");
        $this->isUrl = ($lineCount == 0 && filter_var($href, FILTER_VALIDATE_URL) !== false);
        $this->isFile = (! $this->isUrl && $lineCount == 0 && file_exists($href));
        $this->isRawSrc = (! $this->isUrl && ! $this->isFile);
    }

    /**
     * Getter for Document Source
     *
     * @return string
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * Retrieve source code from a document hypertext reference
     *
     * @param string $href
     *            Hypertext reference can be a URL, a path to a file
     *            or raw source text.
     * @throws Exception
     * @return string|boolean Returns boolean FALSE on failure.
     */
    protected function resolveDocumentSource($href)
    {
        $this->setHrefType($href);
        switch (true) {
            case $this->isUrl:
                if ($src = $this->getRemoteDocumentSource($href)) {
                    return $src;
                } else {
                    throw new \Exception('Could not retrieve source from URL:' . $href);
                }
                return false;
            case $this->isFile:
                return file_get_contents($href);
            case $this->isRawSrc:
                return (string) $href;
        }
    }

    /**
     * Retrieves a remote URL source
     *
     * @param string $href            
     * @return boolean|string Returns false on failure.
     */
    protected function getRemoteDocumentSource($href)
    {
        return function_exists('curl_version') ? $this->getDocumentSourceViaCurl($href) : $this->getDocumentSourceViaStream($href);
    }

    /**
     * Retrieves an URL source via file_get_contents
     *
     * @param string $href            
     * @return boolean|string Returns false on failure.
     */
    private function getDocumentSourceViaStream($href)
    {
        if (filter_var($href, FILTER_VALIDATE_URL) === false) {
            return false;
        }
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => "Content-Type: text/html; charset=utf-8\r\n" . "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\r\n" . "Accept-Encoding: gzip, deflate\r\n" . "Pragma: no-cache\r\n" . "Connection: close\r\n"
            )
        );
        $context = stream_context_create($opts);
        $src = @file_get_contents($href, false, $context);
        foreach ($http_response_header as $c => $h) {
            if (stristr($h, 'content-encoding') && stristr($h, 'gzip')) {
                $src = gzinflate(substr($src, 10, - 8));
            }
        }
        return ($src) ? (string) $src : false;
    }

    /**
     * Retrieves an URL source via cURL
     *
     * @param string $href            
     * @return boolean|string Returns false on failure.
     */
    private function getDocumentSourceViaCurl($href)
    {
        if (filter_var($href, FILTER_VALIDATE_URL) === false) {
            return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $href);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        $src = curl_exec($ch);
        curl_close($ch);
        return ($src) ? (string) $src : false;
    }

    /**
     * Renders the document
     *
     * @return string
     */
    public function render($format = '')
    {
        $this->fileHash = sha1($this->src);
        return $this->src;
    }

    /**
     * Renders the document to the storage cache.
     *
     * @param string $format            
     * @return string|boolean Returns the document sha1 hash on success, boolean FALSE on failure.
     */
    public function renderToStorage($format = '')
    {
        $documentContent = $this->render($format);
        $documentContentHash = $this->fileHash;
        $documentFilePath = $this->getDocumentFilePathByHash($documentContentHash);
        // save plain
        if (! file_exists($documentFilePath)) {
            file_put_contents($documentFilePath, $documentContent);
        }
        // save compressed
        if (! file_exists($documentFilePath . '.gz')) {
            file_put_contents($documentFilePath . '.gz', gzcompress($documentContent, 9));
        }
        return (file_exists($documentFilePath) && file_exists($documentFilePath . '.gz')) ? $documentContentHash : false;
    }

    /**
     * Returns the document storage file path.
     *
     * @param string $shaHash            
     * @return string
     */
    protected function getDocumentFilePathByHash($shaHash)
    {
        $documentExtension = defined(get_class($this) . '::Extension') ? '.' . constant(get_class($this) . '::Extension') : '';
        return rtrim($this->cachePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $shaHash . $documentExtension;
    }

    /**
     * Outputs the document to browser.
     */
    public function output($format = '')
    {
        // Make sure we have a rendered file to serve
        if (is_null($this->fileHash)) {
            $this->renderToStorage($format);
        }
        $documentFilePath = $this->getDocumentFilePathByHash($this->fileHash);
        if (! file_exists($documentFilePath)) {
            $this->renderToStorage($format);
        }
        
        // Accept gzip if requested.
        $gz = false;
        if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') && (ini_get('zlib.output_compression') == '0')) {
            if (file_exists($documentFilePath . '.gz')) {
                $documentFilePath = $documentFilePath . '.gz';
                $gz = true;
            }
        }
        
        if ($mTime = filemtime($documentFilePath)) {
            $etag = sha1($this->fileHash . $documentFilePath . $mTime);
            $mTime = gmdate('D, d M Y H:i:s ', $mTime) . 'GMT';
            $if_modified_since = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false;
            $if_none_match = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? $_SERVER['HTTP_IF_NONE_MATCH'] : false;
            if ($if_none_match) {
                $if_none_match = trim($if_none_match, '"');
                $if_none_match = preg_replace('/-gzip$/', '', $if_none_match);
            }
            if ((($if_none_match && $if_none_match == $etag) || (! $if_none_match)) && ($if_modified_since && $if_modified_since == $mTime)) {
                header($_SERVER["SERVER_PROTOCOL"] . ' 304 Not Modified');
                exit();
            }
            if ($fh = fopen($documentFilePath, 'rb')) {
                header($_SERVER["SERVER_PROTOCOL"] . " 200 Ok");
                header("ETag: \"{$etag}\"");
                if (defined(get_class($this) . '::MimeType')) {
                    header("Content-Type: " . constant(get_class($this) . '::MimeType'));
                }
                header("Content-Length: " . filesize($documentFilePath));
                header("Last-Modified: " . $mTime);
                header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + (60 * 60) * 24 * 7 * 4)); // Cache for a month or so
                if ($gz) {
                    header("Content-Encoding: gzip");
                }
                fpassthru($fh);
                exit();
            }
        }
        
        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
        exit();
    }

    /**
     * Casts the document to string
     */
    public function __toString()
    {
        return $this->render();
    }
}