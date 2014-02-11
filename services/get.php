<?php

$o_books = new SmartXML(file_get_contents("../data/books.xml"));

$book_id = isset(Router::$uri[1]) ? Router::$uri[1] : 0;

// XPATH query
$results = $o_books->xpath->query('/catalog/book[@id=\''.$book_id.'\']');

if($results->count())
{
    if (RESPONSE_MODE == 'json') {
        // Capture output
        // This is a trick to avoid implementing another method similar to __toString()
        // Let's assume that a Javascript will handle the XML response sent in the result field
        // The error_code is added in case we want have a multilingual and we want multilingual error messages
        // (Javascript would translate the code to the error message in the language)
        ob_start();
        echo $results->first();
        $s_xml = ob_get_clean();

        $st_json_response = Array(  'operation'     => 'get',
                                    'status'        => 'ok',
                                    'result'        => $s_xml,
                                    'error_code'    => 0,
                                    'error_message' => '');

        echo json_encode($st_json_response);
    } else {
        echo $results->first();
    }

}
else
{
    if (RESPONSE_MODE == 'json') {
        $st_json_response = Array(  'operation'     => 'get',
                                    'status'        => 'ko',
                                    'result'        => IO::error('Book not found'),
                                    'error_code'    => 100,
                                    'error_message' => 'Book not found');

        echo json_encode($st_json_response);
    } else {
        echo IO::error('Book not found');
    }

}