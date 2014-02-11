<?php

$o_books = new SmartXML(file_get_contents('../data/books.xml'));


$validFilters = array_flip(array('author','genre','price'));
$filters = array_intersect_key($_GET,$validFilters);

$i_num_results_per_page = 5;
$s_page = (isset($_GET['page']) ? $_GET['page'] : '0');

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

$i_counter = 0;
$i_page = 1;
$b_page_results_printed = false;
if($results->count())
{
        echo '<results>'."\n";
        foreach($results as $book) {
            $i_counter++;
            if ($i_counter > $i_num_results_per_page) {
                $i_counter = 0;
                $i_page++;
            }

            if (intval($s_page) == 0 || $i_page == intval($s_page)) {
                $b_page_results_printed = true;

                echo $book."\n";
            } else {
                // We don't have to print
                // We check if we printed before, in that case do not waste more CPU on useless cycles
                if ($b_page_results_printed == true) {
                    break;
                }
            }

        }
        echo '</results>';
}
else
{
	echo IO::error('no matching results');
}