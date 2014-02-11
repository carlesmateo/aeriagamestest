<?php

$o_books = new SmartXML(file_get_contents('../data/books.xml'));


$validFilters = array_flip(array('author','genre','price'));
$filters = array_intersect_key($_GET,$validFilters);

// XPATH query construction
$xquery = '/catalog/book';
$s_subfilter = '';

if(!empty($filters))
{
    $b_filter_applied = false;
	if (isset($filters['genre'])) {
        $s_subfilter .= '[genre=\''.$filters['genre'].'\'';
        $b_filter_applied = true;
    }
    if (isset($filters['author'])) {
        if ($b_filter_applied == false) {
            $s_subfilter .= '[';
        } else {
            $s_subfilter .= ' and ';
        }
        $s_subfilter .= 'author=\''.$filters['author'].'\'';
        $b_filter_applied = true;
    }
    if (isset($filters['price'])) {
        if ($b_filter_applied == false) {
            $s_subfilter .= '[';
        } else {
            $s_subfilter .= ' and ';
        }
        $s_subfilter .= 'price=\''.$filters['price'].'\'';
        $b_filter_applied = true;
    }

    $s_subfilter .= ']';

}

$xquery .= $s_subfilter;

// get results
$results = $o_books->xpath->query($xquery);

if($results->count())
{
	echo '<results>';
	foreach($results as $book)
		echo $book."\n";
	echo '</results>';
}
else
{
	echo IO::error('no matching results');
}