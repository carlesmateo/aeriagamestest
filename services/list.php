<?php

$books = new SmartXML(file_get_contents("../data/books.xml"));


$validFilters = array_flip(array("author","genre","price"));
$filters = array_intersect_key($_GET,$validFilters);

// XPATH query construction
$xquery = "/catalog/book";
if(!empty($filters))
{
	;
}

// get results
$results = $books->xpath->query($xquery);
//echo $xquery;

if($results->count())
{
	echo "<results>";
	foreach($results as $book)
		echo $book."\n";
	echo "</results>";
}
else
{
	echo IO::error("no matching results");
}