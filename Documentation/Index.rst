====================================
HTML Mail Client Rendering Simulator
====================================

.. _content:

Contents
========

.. contents::


.. _introduction:

Introduction
============

.. _what-it-does:

What does it do?
----------------

The HTML Mail Client Rendering Simulator is at foremost a PHP library to parse 
DOM elements and CSS selectors and properties in HTML files to replicate the 
rendering nuances of numerous mail clients in a modern web browser.

Installation
============

The library can be installed via composer.

.. code::
   
   composer install marvin-martian/html-mail-client-rendering-simulator

Quick-Start
===========

The sample GUI should pretty much work out of the box. You may wish to set your 
own writable cache directory and path to the premailer ruby gem. Premailer is 
not required but the supplied newsletter templates do require it to render
correctly.

Usage
=====

To use the PHP library is relatively straight forward.

.. code-block:: php
   
   // create an instance of the class with an URL or file path to your HTML document
   $mailClientSimulator = new HtmlMailClientRenderingSimulator($HTMLFile);
   
   // parse the HTML document with a mail client.
   $mailClientSimulator->parseHtmlByMailClientId($mailClientId);
   
   // output the resulting HTML.
   $mailClientSimulator->outputHtml();
   

Any external CSS files will be rewritten and cached. The document will request
the CSS file by a request param. (?cssFileHash=###hash###) The hash can be fed
back to the parsed instance to return the modified CSS content.

.. code-block:: php
   
   // output the resulting CSS.
   $mailClientSimulator->outputExternalCSS(###hash###)

The full PHP API can be extracted from the source code with Doxygen.

Notes
=====

This simulation is of course not 100% accurate. It does not represent the 
default system fonts and sizes of the various mail clients and platforms upon 
which they run. Also, at the time of writing, the "CSS Support Guide for Email 
Clients" by Campaign Monitor is not entirely comprehensive of all facets of HTML
and CSS in the various mail clients. For example: @import testing is not 
included.


Despite that it is good enough to see if your HTML mail templates are breaking.

