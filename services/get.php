<?php

$books = new SmartXML(file_get_contents("../data/books.xml"));

$book_id = isset(Router::$uri[1]) ? Router::$uri[1] : 0;

// XPATH query
$results = $books->xpath->query("/catalog/book");

if($results->count())
{
	echo $results->first();
}
else
{
	echo IO::error("Book not found");
}