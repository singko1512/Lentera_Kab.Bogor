<?php
$xml = file_get_contents('temp_docx/word/document.xml');
$doc = new DOMDocument();
$doc->loadXML($xml);
$xpath = new DOMXPath($doc);
$xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
$nodes = $xpath->query('//w:tcPr');
foreach ($nodes as $node) {
    echo 'tcPr: ';
    foreach ($node->childNodes as $child) {
        if ($child->nodeType == XML_ELEMENT_NODE) {
            echo $child->nodeName . ' ';
        }
    }
    echo PHP_EOL;
}
