<?php

// Load language rules
require_once('../src/LanguageDetector.php');
$detector = new \inspektorsowa\LanguageDetector\LanguageDetector();
$detector->loadRulesFromDirectory(realpath('../src/lang-rules'));

// Download random Wikipedia page
$locales = ['en', 'pl', 'de', 'es'];
shuffle($locales);
$randomLocale = reset($locales);
$url = 'https://'. $randomLocale .'.wikipedia.org/wiki/Special:Random';
$articleSourceCode = file_get_contents($url);
$articleSourceCode = strtr($articleSourceCode, ['&reg;' => '']);
$xml = simplexml_load_string($articleSourceCode);
$bodyContent = $xml->xpath('//*[@id=\'bodyContent\']');
$content = strip_tags($bodyContent[0]->asXml());

// Detect language

$result = $detector->detect($content);
$detectedLocale = $result['lang'];

// Show results in console
echo $randomLocale . ' == ' . $detectedLocale . PHP_EOL;
if ($randomLocale != $detectedLocale) {
    echo '--- ERROR ------------------------------------------' . PHP_EOL;
    var_dump($results);
    echo '-------------------------------------------------------' . PHP_EOL;
}