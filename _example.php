<?php
// create instance
$wrapper = new \Mhr\DNSParser\DNSParser();
//  load from file
$wrapper->loadFromFile('file address');
// load from text
$wrapper->loadFromText(" sample dns text ");
// parse the txt
$wrapper->parse();


// now we can fetch all the DNS instance with :
$wrapper->fetchAll();

// or fetch then by filtering their properties
$wrapper->fetchByValue("");
$wrapper->fetchByType("");
$wrapper->fetchByName("");

//fetch all the types
$wrapper->fetchTypes();


// fetch functions returns an array of the DNS class
// DNS class contains this methods

$dns = $wrapper->fetchAll()[0]; // get dns record

$dns->getName(); // return name of the DNS
$dns->getValue(); // return Value of the DNS record
$dns->getType(); // return type of this record
$dns->getPort(); // return port number if is supported in this type of record
$dns->getPriority(); // returns priority if its supported
$dns->getWeight(); // and also weight like previous method

