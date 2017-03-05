<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'DBConnection.php';
require 'ParserCollector.php';
require 'Parser.php';
//require 'Parser/simple_html_dom.php';
//require 'BrandRepository.php';
//require 'GenerationRepository.php';


$a = 1;
$dbConnection = DBConnection::getInstance();
$connection = $dbConnection->getConnection();

//$a = 1;
$parserCollector = new ParserCollector($connection);
$parser = new Parser($parserCollector);
$parser->parse();
$z = 1;


//$brands = $parser->parseBrands('https://www.drive2.ru/cars/?all');
//$models = $parser->parseModels($brands);
//$cars = $parser->parseCars(array());
//$generations = $parser->parseGenerations($models);
echo 'end';
die();
