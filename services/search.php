<?php
/**
 * Creator:      Carles Mateo
 * Date:         2014-02-10 23:53
 * Last Updater: Carles Mateo
 * Last Updated: 2014-02-11 02:33
 * Filename:     search.php
 * Description:  Search Service
 *               Test it with_
 *               http://html.aeriatest.loc/search/description+author?q=zo
 *               http://json.aeriatest.loc/search/?q=zo
 */

$o_books = new SmartXML(file_get_contents('../data/books.xml'));

$s_subfilter = Router::$uri[1];
$s_search = $_GET['q'];

$i_num_results_per_page = 5;
$s_page = (isset($_GET['page']) ? $_GET['page'] : '0');

$st_valid_subfilters = Array('author', 'title', 'genre', 'price', 'publish_date', 'description');

if ($s_subfilter == '') {
    // No subfilters, that means search in all the scopes.
    $st_subfilter = $st_valid_subfilters;
} else {
    $st_subfilter = explode('+', $s_subfilter);
}

// Check security
$b_bad_scope = false;
$s_xpath_filter = '';
foreach($st_subfilter as $i_key=>$s_subfilter_to_check) {
    if (!in_array($s_subfilter_to_check, $st_valid_subfilters, true)) {
        $b_bad_scope = true;
        break;  // Do not waste CPU
    } else {
        // Everything Ok, we build the query
        if ($s_xpath_filter == '') {
            $s_xpath_filter = '['.$s_subfilter_to_check.'[contains(.,\''.$s_search.'\')]';
        } else {
            $s_xpath_filter .= ' or ';
            $s_xpath_filter .= $s_subfilter_to_check.'[contains(.,\''.$s_search.'\')]';
        }
    }
}

$s_xpath_filter .= ']';

if ($b_bad_scope == false) {
    // XPATH query construction
    $s_xquery = '/catalog/book'.$s_xpath_filter;
    // Example:
    // /catalog/book[author[contains(.,'zombies')] or title[contains(.,'zombies')] or genre[contains(.,'zombies')] or price[contains(.,'zombies')] or publish_date[contains(.,'zombies')] or description[contains(.,'zombies')]]

    // get results
    $results = $o_books->xpath->query($s_xquery);

    $i_counter = 0;
    $i_page = 1;
    $b_page_results_printed = false;

    if($results->count())
    {
        echo '<results>';
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

} else {
    // If a wrong parameter is sent, we will return this
    // Fe: http://html.aeriatest.loc/search/description+author+invented?q=zo
    echo IO::error('wrong parameters');
}

