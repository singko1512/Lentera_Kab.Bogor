<?php
$content = file_get_contents('C:\Users\Makarimu Samarkandi\.gemini\antigravity-ide\brain\974689ef-0ac7-4941-98a0-d1fbcb302930\scratch\docx_document_pretty.xml');

// 1. Logo size
$content = str_replace('<wp:extent cx="635000" cy="801370"/>', '<wp:extent cx="647700" cy="820300"/>', $content);
$content = str_replace('<a:ext cx="635000" cy="801370"/>', '<a:ext cx="647700" cy="820300"/>', $content);

// 2. Kop table grid widths
$content = str_replace('<w:gridCol w:w="850"/>
        <w:gridCol w:w="8425"/>', '<w:gridCol w:w="1125"/>
        <w:gridCol w:w="8400"/>', $content);

// 3. Kop table column widths (text)
// NO tcMar THIS TIME!
$content = str_replace('<w:tcW w:w="8425" w:type="dxa"/>
            <w:vAlign w:val="center"/>', '<w:tcW w:w="8400" w:type="dxa"/>
            <w:vAlign w:val="center"/>', $content);

// 4. Logo alignment from left to center
$content = preg_replace('/(<w:tcPr>\s*<w:tcW w:w="1125" w:type="dxa"\/>\s*<w:vAlign w:val="center"\/>\s*<\/w:tcPr>\s*<w:p>\s*<w:pPr>\s*)<w:jc w:val="left"\/>/', '$1<w:jc w:val="center"/>', $content);

// 5. Meta table grid
$content = str_replace('<w:gridCol w:w="850"/>
        <w:gridCol w:w="180"/>
        <w:gridCol w:w="8495"/>', '<w:gridCol w:w="1125"/>
        <w:gridCol w:w="225"/>
        <w:gridCol w:w="8175"/>', $content);

// Meta table columns
$content = str_replace('<w:tcW w:w="850" w:type="dxa"/>', '<w:tcW w:w="1125" w:type="dxa"/>', $content);
$content = str_replace('<w:tcW w:w="8495" w:type="dxa"/>', '<w:tcW w:w="8175" w:type="dxa"/>', $content);

// 6. Data table grid
$content = str_replace('<w:gridCol w:w="2150"/>
        <w:gridCol w:w="180"/>
        <w:gridCol w:w="7195"/>', '<w:gridCol w:w="2325"/>
        <w:gridCol w:w="225"/>
        <w:gridCol w:w="6975"/>', $content);
$content = str_replace('<w:tcW w:w="2150" w:type="dxa"/>', '<w:tcW w:w="2325" w:type="dxa"/>', $content);
$content = str_replace('<w:tcW w:w="180" w:type="dxa"/>', '<w:tcW w:w="225" w:type="dxa"/>', $content);
$content = str_replace('<w:tcW w:w="7195" w:type="dxa"/>', '<w:tcW w:w="6975" w:type="dxa"/>', $content);

// 7. Page Margins
$content = str_replace('<w:pgMar w:top="1134" w:right="1134" w:bottom="1134" w:left="1134" w:header="708" w:footer="708" w:gutter="0"/>', '<w:pgMar w:top="680" w:right="1134" w:bottom="1247" w:left="1247" w:header="360" w:footer="720" w:gutter="0"/>', $content);

file_put_contents('C:\Users\Makarimu Samarkandi\.gemini\antigravity-ide\brain\974689ef-0ac7-4941-98a0-d1fbcb302930\scratch\clean_document.xml', $content);
echo "XML Patched without tcMar!\n";
