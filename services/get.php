<?php

$o_books = new SmartXML(file_get_contents("../data/books.xml"));

$book_id = isset(Router::$uri[1]) ? Router::$uri[1] : 0;

// XPATH query
$results = $o_books->xpath->query('/catalog/book[@id=\''.$book_id.'\']');

if($results->count())
{
	echo $results->first();
}
else
{
	echo IO::error('Book not found');
}