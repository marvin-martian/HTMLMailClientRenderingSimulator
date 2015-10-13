<?php
/*
 * Example HTML Mail Client Rendering Simulator GUI
 * This is very rough and dirty prototype coded in a hurry.
 *
 * @author Marvin-Martian https://github.com/marvin-martian
 * @copyright (c) 2015 Marvin Martian <marvin-martian@users.noreply.github.com>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
use MarvinMartian\HtmlMailClientRenderingSimulator\HtmlMailClientRenderingSimulator;

require_once (__DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

////////////////////////////////////////////////////////
// Custom Options (Change these if you need to)
//
// $pathToPremailer = 'path/to/your/premailer/ruby/gem';
// $tempDirectory = '/your/temp/directory';
//
////////////////////////////////////////////////////////


if (! isset($tempDirectory)) {
    if (! ($tempDirectory = @sys_get_temp_dir())) {
        echo 'Need a temporary storage directory.';
        exit();
    }
}
$templateRequest = '';
if (isset($_REQUEST['template'])) {
    $templateRequest = $_REQUEST['template'];
}

// Scan the templates directory
$templateFilesSelect = '';
$templatesDirectory = __DIR__ . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'Public' . DIRECTORY_SEPARATOR . 'SampleNewsletters' . DIRECTORY_SEPARATOR . 'templates';
if ($scanDirTemplates = scandir($templatesDirectory)) {
    $templatesFiles = array();
    $scanDirTemplates = array_diff($scanDirTemplates, array(
        '..',
        '.'
    ));
    foreach ($scanDirTemplates as $dir) {
        if (is_dir($templatesDirectory . DIRECTORY_SEPARATOR . $dir)) {
            $templatesFiles[$dir] = array_diff(scandir($templatesDirectory . DIRECTORY_SEPARATOR . $dir), array(
                '..',
                '.'
            ));
        }
    }
    
    $templateFilesSelectOptions = '';
    $templateId = 0;
    foreach ($templatesFiles as $key => $value) {
        $templateFilesSelectOptions .= '<optgroup label="' . ucfirst($key) . '">';
        foreach ($value as $template) {
            $selected = ($templateId == $templateRequest) ? ' selected="selected"' : '';
            if ($templateId == $templateRequest) {
                $templateFile = $key . DIRECTORY_SEPARATOR . $template;
            }
            $templateFilesSelectOptions .= '<option value="' . $templateId . '"' . $selected . '>' . $key . '-' . $template . '</option>';
            $templateId ++;
        }
        $templateFilesSelectOptions .= '</optgroup>';
    }
    
    $templateFilesSelect = '<select id="template-select" name="template">' . $templateFilesSelectOptions . '</select>';
}

$templateRequest = '&template=' . $templateRequest;

// Switch between test and samples
if (isset($_REQUEST['sampleFile']) && $_REQUEST['sampleFile'] == 'sampleNewsletter') {
    $sampleTestMatrix = '';
    $sampleTestNewsletter = ' checked';
    $sampleFile = 'sampleNewsletter';
    
    if (! isset($templateFile)) {
        $templateFile = 'airmail' . DIRECTORY_SEPARATOR . 'confirm.html';
    }
    $HTMLFile = __DIR__ . DIRECTORY_SEPARATOR . 'Resources/Public/SampleNewsletters/templates/' . $templateFile;
    /*
     * // Can also get the template by URL if you want.
     * if (!isset($templateFile)) {
     * $templateFile = 'airmail/confirm.html';
     * }
     * $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
     * $HTMLFile = $protocol.$_SERVER['HTTP_HOST'].'/Resources/Public/SampleNewsletters/templates/'.$templateFile;
     */
} else {
    $sampleTestMatrix = ' checked';
    $sampleTestNewsletter = '';
    $sampleFile = 'testMatrix';
    $HTMLFile = __DIR__ . DIRECTORY_SEPARATOR . 'cssTestMatrix.html';
}

/*
 * [OPTIONAL] If you have premailer ruby gem installed you can run the template through it.
 * @see https://github.com/premailer/premailer
 */
if (!isset($pathToPremailer)) {
    $pathToPremailer = '/usr/local/bin/premailer';
}
$hasPremailer = false;
if (is_callable('shell_exec') && false === stripos(ini_get('disable_functions'), 'shell_exec')) {
    $premailerVersion = shell_exec($pathToPremailer . ' -V');
    if (! is_null($premailerVersion)) {
        $hasPremailer = (stripos($premailerVersion, 'premailer') !== false);
    }
}

$premailerCheckBox = '';
$usePremailer = false;
if ($hasPremailer) {
    if (isset($_REQUEST['premailer']) && $_REQUEST['premailer'] == 'premailer' && isset($_REQUEST['sampleFile']) && $_REQUEST['sampleFile'] !== 'testMatrix') {
        $usePremailer = true;
    }
    if ($usePremailer) {
        $requestHash = sha1($HTMLFile);
        $premailerFile = rtrim($tempDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'premailer-' . $requestHash . '.html';
        if (file_exists($premailerFile)) {
            $HTMLFile = $premailerFile;
        } else {
            $premailerHTML = shell_exec($pathToPremailer . ' "' . $HTMLFile . '"');
            if (file_put_contents($premailerFile, $premailerHTML)) {
                $HTMLFile = $premailerFile;
            } else {
                $usePremailer = false;
            }
        }
    }
    $premailerRequest = $usePremailer ? '&premailer=premailer' : '';
    $premailerChecked = $usePremailer ? ' checked="checked"' : '';
    $premailerCheckBox = '<input id="premailer" name="premailer" type="checkbox" value="premailer"' . $premailerChecked . '> <label for="premailer">Use premailer</label>';
}

/**
 * Create an instance of the Mail Simulator
 */
$mailClientSimulator = new HtmlMailClientRenderingSimulator($HTMLFile);

if (isset($_REQUEST['cssFileHash'])) {
    /*
     * Output Sample CSS
     */
    
    $mailClientSimulator->outputExternalCSS($_REQUEST['cssFileHash']);
    exit();
}

if (isset($_REQUEST['mailClient'])) {
    /*
     * Output Sample HTML
     */
    
    // Parse the mailClient
    $mailClientSimulator->parseHtmlByMailClientId($_REQUEST['mailClient']);
    
    // Output the HTML
    $mailClientSimulator->outputHtml();
    exit();
}

if (! isset($_REQUEST['mailClient']) && ! isset($_REQUEST['cssFileHash'])) {
    
    /*
     * Render a GUI
     */
    $head = '<head>';
    $head .= '<meta charset="utf-8">';
    $head .= '<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>';
    $head .= '<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>';
    $head .= '<script src="Resources/Public/GUI/js/emulator.min.js"></script>';
    $head .= '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.theme.min.css">';
    $head .= '<script>var testHashMap =' . $mailClientSimulator->getMailClientCollection()->getMailClientJSHashMap() . '</script>';
    $head .= '<link rel="stylesheet" href="Resources/Public/GUI/css/style.css">';
    $head .= '<style>';
    $head .= 'html, body {background-color:#fdf6e3;}';
    $head .= '#container {width:800px;margin: 1em auto;}';
    $head .= '#iframe-container {margin-bottom: 40px;width:800px;height:300px;}';
    $head .= 'iframe {width:100%;height:100%}';
    $head .= '.ui-widget-content {position:relative;padding-bottom:20px;}';
    $head .= '.ui-resizable-se {position:absolute;right:1px;cursor:se-resize;}';
    $head .= '';
    $head .= '</style>';
    $head .= '<title>HTML Mail Client Rendering Simulator</title>';
    $head .= '</head>';
    
    $form = '<form style="margin-bottom:1em;text-align:right;">';
    $form .= '<div style="text-align:left;">';
    
    $form .= '<input id="sampleTestMatrix" type="radio" name="sampleFile" value="testMatrix"' . $sampleTestMatrix . '> <label for="sampleTestMatrix">Test Matrix</label>&nbsp;&nbsp;';
    $form .= '<input id="sampleTestNewsletter" type="radio" name="sampleFile" value="sampleNewsletter"' . $sampleTestNewsletter . '> <label for="sampleTestNewsletter">Sample Newsletter</label>';
    $form .= '<br /><br /><label for="template">Template: </label>' . $templateFilesSelect;
    $form .= ' &nbsp;&nbsp;' . $premailerCheckBox . '';
    $form .= '</div>';
    $form .= '<label for="mailclient-select">Mail Client: </label>';
    $form .= '<select id="mailclient-select" name="mailclient">';
    $form .= '<option value="none" style="font-style:italic;">None</option>';
    $form .= $mailClientSimulator->getMailClientCollection()->getMailClientSelectOptions();
    $form .= '</select>';
    $form .= '</form>';
    
    $iframe = '<div id="iframe-container" class="ui-widget-content" style="background:none;background-color:#ddd;"><iframe id="iframe" style="background-color:#fff;" src=""></iframe></div>';
    echo "<!DOCTYPE html>\n<html>" . $head . "<body><noscript ><div style=\"background-color:yellow;color:red;padding:1em;width:100%;display:block;text-align:center;\">Javascript needs to be enabled.</div></noscript><div id=\"container\"> <div id=\"main\" role=\"main\"><h4>Marvin Martian's</h4><h2>HTML Mail Client Rendering Simulator <span style=\"font-size:0.5em;\">(v1.0.0)</span></h2><br ><p>This simulator is best used from a modern HTML5/CSS-3 standards compliant desktop web browser.</p></br>" . $form . $iframe . "<footer><p style=\"line-height:1.6em;\">This simulator is based upon the \"CSS Support Guide for Email Clients\" by <a href=\"https://www.campaignmonitor.com/css/\"  target=\"_blank\">Campaign Monitor</a><br />Sample newsletter templates by <a href=\"https://github.com/sendwithus/templates\"  target=\"_blank\">sendwithus/templates</a> (License: <a href=\"http://www.apache.org/licenses/LICENSE-2.0\" target=\"_blank\">Apache 2.0</a>) <br />Excel parsing lib by <a href=\"https://github.com/PHPOffice/PHPExcel\" target=\"_blank\">PHPOffice/PHPExcel</a><br />CSS parsing lib by <a href=\"https://github.com/sabberworm/PHP-CSS-Parser/\" target=\"_blank\">sabberworm/PHP-CSS-Parser</a><br />Premailer Ruby Gem support <a href=\"https://github.com/premailer/premailer\" target=\"_blank\">premailer/premailer</a><br /> Interface CSS styles Solarized by <a href=\"http://ethanschoonover.com/\" target=\"_blank\">Ethan Schoonover</a><br /><br />Copyright (c) 2015 <a href=\"https://github.com/marvin-martian\" target=\"blank\"> Marvin Martian</a><br> License: http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later</p></footer></div></div></body></html>";
    exit();
}

exit();