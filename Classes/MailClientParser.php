<?php
namespace MarvinMartian\HtmlMailClientRenderingSimulator;

/**
 * HtmlDocumentParser for HTML Mail Client Rendering Simulator.
 * 
 * @author Marvin-Martian https://github.com/marvin-martian
 * @copyright (c) 2015 Marvin Martian <marvin-martian@users.noreply.github.com>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class MailClientParser
{

    /**
     * [7ab32a7e] Test-1: Responsive - @media
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_7ab32a7e(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector('@media');
    }

    /**
     * [77d7804e] Test-2: Style element - <style> in <head>
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_77d7804e(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeHtmlTagByXpath('//head/style[contains(@media,"all") or contains(@media,"screen") or contains(@type,"text/css") or not(@*)]');
    }

    /**
     * [ecf44f62] Test-3: Style element - <style> in <body>
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_ecf44f62(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeHtmlTagByXpath('//body/style[contains(@media,"all") or contains(@media,"screen") or contains(@type,"text/css") or not(@*)]');
    }

    /**
     * [686e5a90] Test-4: Link Element - <link> in <head>
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_686e5a90(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeHtmlTagByXpath('//head/link[@rel="stylesheet" and @href]');
    }

    /**
     * [2dee6269] Test-5: Link Element - <link> in <body>
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_2dee6269(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeHtmlTagByXpath('//body/link[@rel="stylesheet" and @href]');
    }

    /**
     * [9caf0af0] Test-6: Selectors - *
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_9caf0af0(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector(' * ');
        $htmlDoc->removeCssSelector('* ');
        $htmlDoc->removeCssSelector(' *');
    }

    /**
     * [67d1f2b8] Test-7: Selectors - E
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_67d1f2b8(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector('/.+/i', true);
    }

    /**
     * [b58045b6] Test-8: Selectors - E[foo]
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_b58045b6(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector('[');
    }

    /**
     * [1df64038] Test-9: Selectors - E[foo="bar"]
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_1df64038(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector('=');
    }

    /**
     * [983f86ca] Test-10: Selectors - E[foo~="bar"]
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_983f86ca(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector('~=');
    }

    /**
     * [ebccbef8] Test-11: Selectors - E[foo^="bar"]
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_ebccbef8(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector('^=');
    }

    /**
     * [a638e828] Test-12: Selectors - E[foo$="bar"]
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_a638e828(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector('$=');
    }

    /**
     * [8f0f583a] Test-13: Selectors - E[foo*="bar"]
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_8f0f583a(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector('*=');
    }

    /**
     * [b20730b4] Test-14: Selectors - E:nth-child(n)
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_b20730b4(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector(':nth-child(');
    }

    /**
     * [e913f855] Test-15: Selectors - E:nth-last-child(n)
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_e913f855(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector(':nth-last-child(');
    }

    /**
     * [fb5cbeae] Test-16: Selectors - E:nth-of-type(n)
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_fb5cbeae(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector(':nth-of-type(');
    }

    /**
     * [563aaf46] Test-17: Selectors - E:nth-last-of-type(n)
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_563aaf46(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector(':nth-last-of-type(');
    }

    /**
     * [31e4a392] Test-18: Selectors - E:first-child
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_31e4a392(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector(':first-child');
    }

    /**
     * [ae360614] Test-19: Selectors - E:last-child
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_ae360614(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector(':last-child');
    }

    /**
     * [ab9f33e9] Test-20: Selectors - E:first-of-type
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_ab9f33e9(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector(':first-of-type');
    }

    /**
     * [ba9f37d7] Test-21: Selectors - E:last-of-type
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_ba9f37d7(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector(':last-of-type');
    }

    /**
     * [203d2628] Test-22: Selectors - E:empty
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_203d2628(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector(':empty');
    }

    /**
     * [9a0ceb70] Test-23: Selectors - E:link
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_9a0ceb70(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector(':link');
    }

    /**
     * [68a0d37a] Test-24: Selectors - E:visited
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_68a0d37a(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector(':visited');
    }

    /**
     * [c997d947] Test-25: Selectors - E:active
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_c997d947(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector(':active');
    }

    /**
     * [fc356b6f] Test-26: Selectors - E:hover
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_fc356b6f(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector(':hover');
    }

    /**
     * [78fa5c2b] Test-27: Selectors - E:focus
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_78fa5c2b(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector(':focus');
    }

    /**
     * [19a2229a] Test-28: Selectors - E:target
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_19a2229a(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector(':target');
    }

    /**
     * [f370d5ef] Test-29: Selectors - E::first-line
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_f370d5ef(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector(':first-line');
    }

    /**
     * [a97ea287] Test-30: Selectors - E::first-letter
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_a97ea287(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector(':first-letter');
    }

    /**
     * [8293eee4] Test-31: Selectors - E::before
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_8293eee4(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector(':before');
    }

    /**
     * [2c3e6c38] Test-32: Selectors - E::after
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_2c3e6c38(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector(':after');
    }

    /**
     * [72ebb98f] Test-33: Selectors - E.classname
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_72ebb98f(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector('.');
    }

    /**
     * [56f321f1] Test-34: Selectors - E#id
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_56f321f1(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector('#');
    }

    /**
     * [479f5a3e] Test-35: Selectors - E:not(s)
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_479f5a3e(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector(':not(');
    }

    /**
     * [1062cc7e] Test-36: Selectors - E F
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_1062cc7e(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector(' ');
    }

    /**
     * [9255cdd9] Test-37: Selectors - E > F
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_9255cdd9(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector('>');
    }

    /**
     * [c1c902af] Test-38: Selectors - E + F
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_c1c902af(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector('+');
    }

    /**
     * [6545871c] Test-39: Selectors - E ~ F
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_6545871c(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector('~');
    }

    /**
     * [0494e178] Test-40: Text & Fonts - @font-face
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_0494e178(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssSelector('@font-face');
    }

    /**
     * [759976bd] Test-41: Text & Fonts - direction
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_759976bd(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('direction');
    }

    /**
     * [3a74c28b] Test-42: Text & Fonts - font
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_3a74c28b(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('font');
    }

    /**
     * [10e6a066] Test-43: Text & Fonts - font-family
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_10e6a066(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('font-family');
    }

    /**
     * [838369bc] Test-44: Text & Fonts - font-style
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_838369bc(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('font-style');
    }

    /**
     * [b018aa62] Test-45: Text & Fonts - font-variant
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_b018aa62(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('font-variant');
    }

    /**
     * [b313d518] Test-46: Text & Fonts - font-size
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_b313d518(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('font-size');
    }

    /**
     * [336fb543] Test-47: Text & Fonts - font-weight
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_336fb543(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('font-weight');
    }

    /**
     * [3f2a28a9] Test-48: Text & Fonts - letter-spacing
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_3f2a28a9(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('letter-spacing');
    }

    /**
     * [01c23cbf] Test-49: Text & Fonts - line-height
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_01c23cbf(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('line-height');
    }

    /**
     * [70de8d97] Test-50: Text & Fonts - text-align
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_70de8d97(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('text-align');
    }

    /**
     * [8a67db55] Test-51: Text & Fonts - text-decoration
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_8a67db55(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('text-decoration');
    }

    /**
     * [c26dc4a9] Test-52: Text & Fonts - text-indent
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_c26dc4a9(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('text-indent');
    }

    /**
     * [04f887a4] Test-53: Text & Fonts - text-overflow CSS3
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_04f887a4(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('text-overflow');
    }

    /**
     * [6b640d21] Test-54: Text & Fonts - text-shadow CSS3
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_6b640d21(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('text-shadow');
    }

    /**
     * [039c0907] Test-55: Text & Fonts - text-transform
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_039c0907(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('text-transform');
    }

    /**
     * [51a13abd] Test-56: Text & Fonts - white-space
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_51a13abd(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('white-space');
    }

    /**
     * [a7b28bb1] Test-57: Text & Fonts - word-spacing
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_a7b28bb1(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('word-spacing');
    }

    /**
     * [90ab9a1e] Test-58: Text & Fonts - word-wrap CSS3
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_90ab9a1e(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('word-wrap');
    }

    /**
     * [9735bd76] Test-59: Text & Fonts - vertical-align
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_9735bd76(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('vertical-align');
    }

    /**
     * [66291fe2] Test-60: Text & Fonts - text-fill-color CSS3
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_66291fe2(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('text-fill-color');
    }

    /**
     * [2fa9ad72] Test-61: Text & Fonts - text-fill-stroke CSS3
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_2fa9ad72(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('text-stroke');
    }

    /**
     * [ac33866b] Test-62: Color & Background - color
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_ac33866b(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('color');
    }

    /**
     * [8485b329] Test-63: Color & Background - background
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_8485b329(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('background');
    }

    /**
     * [10a53a4e] Test-64: Color & Background - background CSS3
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_10a53a4e(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('background');
    }

    /**
     * [53655345] Test-65: Color & Background - background-color
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_53655345(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('background-color');
    }

    /**
     * [5b4eddb6] Test-66: Color & Background - background-image
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_5b4eddb6(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('background-image');
    }

    /**
     * [ed148ab3] Test-67: Color & Background - background-position
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_ed148ab3(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('background-position');
    }

    /**
     * [5bd5da4b] Test-68: Color & Background - background-repeat
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_5bd5da4b(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('background-repeat');
    }

    /**
     * [fb326a76] Test-69: Color & Background - background-size CSS3
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_fb326a76(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('background-size');
    }

    /**
     * [8362f15d] Test-70: Color & Background - HSL Colors CSS3
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_8362f15d(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssPropertyByValueMatch('hsl(');
    }

    /**
     * [36acd75b] Test-71: Color & Background - HSLA Colors CSS3
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_36acd75b(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssPropertyByValueMatch('hsla(');
    }

    /**
     * [b878c35b] Test-72: Color & Background - Opacity CSS3
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_b878c35b(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('opacity');
    }

    /**
     * [d47f49cc] Test-73: Color & Background - RGBA Colors CSS3
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_d47f49cc(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssPropertyByValueMatch('rgba(');
    }

    /**
     * [db71ed66] Test-74: Box Model - border
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_db71ed66(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('border');
    }

    /**
     * [fc6e97cc] Test-75: Box Model - border-color
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_fc6e97cc(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('border-color');
    }

    /**
     * [a4318203] Test-76: Box Model - border-image CSS3
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_a4318203(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('border-image');
    }

    /**
     * [1f7394f7] Test-77: Box Model - border-radius CSS3
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_1f7394f7(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('border-radius');
    }

    /**
     * [b4a347ed] Test-78: Box Model - box-shadow CSS3
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_b4a347ed(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('box-shadow');
    }

    /**
     * [c07639f2] Test-79: Box Model - height
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_c07639f2(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('height');
    }

    /**
     * [109dfea3] Test-80: Box Model - margin
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_109dfea3(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('margin');
    }

    /**
     * [b617eed8] Test-81: Box Model - padding
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_b617eed8(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('padding');
    }

    /**
     * [a3db19e5] Test-82: Box Model - width
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_a3db19e5(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('width');
    }

    /**
     * [e21b6890] Test-83: Box Model - max-width
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_e21b6890(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('max-width');
    }

    /**
     * [d6c81400] Test-84: Box Model - min-width
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_d6c81400(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('min-width');
    }

    /**
     * [dbd11a8d] Test-85: Positioning & Display - bottom
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_dbd11a8d(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('bottom');
    }

    /**
     * [6e9b0c87] Test-86: Positioning & Display - clear
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_6e9b0c87(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('clear');
    }

    /**
     * [f064c3da] Test-87: Positioning & Display - clip
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_f064c3da(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('clip');
    }

    /**
     * [a3368314] Test-88: Positioning & Display - cursor
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_a3368314(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('cursor');
    }

    /**
     * [576fbbe5] Test-89: Positioning & Display - display
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_576fbbe5(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('display');
    }

    /**
     * [033c2272] Test-90: Positioning & Display - float
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_033c2272(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('float');
    }

    /**
     * [d8400de4] Test-91: Positioning & Display - left
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_d8400de4(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('left');
    }

    /**
     * [071cf872] Test-92: Positioning & Display - opacity
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_071cf872(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('opacity');
    }

    /**
     * [869af319] Test-93: Positioning & Display - outline CSS3
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_869af319(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('outline');
    }

    /**
     * [5e86bcb9] Test-94: Positioning & Display - overflow
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_5e86bcb9(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('overflow');
    }

    /**
     * [6052b8cc] Test-95: Positioning & Display - position
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_6052b8cc(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('position');
    }

    /**
     * [6cc5a2f4] Test-96: Positioning & Display - resize CSS3
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_6cc5a2f4(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('resize');
    }

    /**
     * [f9003767] Test-97: Positioning & Display - right
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_f9003767(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('right');
    }

    /**
     * [c2d340f3] Test-98: Positioning & Display - top
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_c2d340f3(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('top');
    }

    /**
     * [3807de0a] Test-99: Positioning & Display - visibility
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_3807de0a(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('visibility');
    }

    /**
     * [717d7a8a] Test-100: Positioning & Display - z-index
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_717d7a8a(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('z-index');
    }

    /**
     * [6c540b7d] Test-101: Lists - list-style-image
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_6c540b7d(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('list-style-image');
    }

    /**
     * [b9f59740] Test-102: Lists - list-style-position
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_b9f59740(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('list-style-position');
    }

    /**
     * [3a3844b6] Test-103: Lists - list-style-type
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_3a3844b6(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('list-style-type');
    }

    /**
     * [17700c8f] Test-104: Tables - border-collapse
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_17700c8f(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('border-collapse');
    }

    /**
     * [cf0675ee] Test-105: Tables - border-spacing
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_cf0675ee(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('border-spacing');
    }

    /**
     * [41447cb4] Test-106: Tables - caption-side
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_41447cb4(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('caption-side');
    }

    /**
     * [7228787f] Test-107: Tables - empty-cells
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_7228787f(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('empty-cells');
    }

    /**
     * [1e628b52] Test-108: Tables - table-layout
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_1e628b52(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeCssProperty('table-layout');
    }

    /**
     * [d84c49a7] Test-109: HTML 5 - <canvas>
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_d84c49a7(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeHtmlTag('canvas');
    }

    /**
     * [4e2bcae6] Test-110: HTML 5 - <audio>
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_4e2bcae6(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeHtmlTag('audio');
    }

    /**
     * [668b4945] Test-111: HTML 5 - <video>
     *
     * @param HtmlDocument $htmlDoc            
     *
     */
    public static function parseRule_668b4945(HtmlDocument &$htmlDoc)
    {
        $htmlDoc->removeHtmlTag('video');
    }
}