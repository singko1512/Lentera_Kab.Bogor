<?php
$xml = file_get_contents('C:\Users\Makarimu Samarkandi\.gemini\antigravity-ide\brain\974689ef-0ac7-4941-98a0-d1fbcb302930\scratch\docx_document_pretty.xml');
$pos = strpos($xml, 'Dokumen ini telah');
if ($pos !== false) {
    echo htmlspecialchars(substr($xml, $pos - 500, 1000));
} else {
    echo 'Not found';
}
