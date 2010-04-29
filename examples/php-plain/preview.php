<?php
require_once dirname(__FILE__) . "/libs/loader.php";
require_once dirname(__FILE__) . "/texyla.php";

$httpRequest = new HttpRequest;
$httpResponse = new HttpResponse;

// validace cfg
$rawCfg = $httpRequest->getPost("cfg");
$cfg = ereg("^[a-z]+$", $rawCfg) ? $rawCfg : null;

// načtení třídy
$texy = TexylaTools::getTexy($cfg);

$httpResponse->setContentType("text/html", "utf-8");

$texyCode = $httpRequest->getPost("texy");
echo $texy->process($texyCode);
