<?php 

use Acme\MovieSearch;

// include the Class
require '../src/MovieSearch.php';


// Get movie by title
// goat
// american pie
// black books
$movies = new MovieSearch('black books');

$results = $movies->getMultiple(array('title', 'year', 'poster'));


if(isset($results) && count($movies > 0)) {

  echo '{' . $results['title'] . '}' . " ";
  echo '[' . '{' . $results['year'] . '}' . ']' . " ";
  echo  '-' . ' {' . $results['poster'] . '}';
  echo "\n";
  echo '=> ' . '{' . count($movies) . '}' . ' result(s) found';
} else {
  echo "Movie not found";
}

