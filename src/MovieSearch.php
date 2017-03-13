<?php 

namespace Acme;

/**
 * Movie Search Class
 *
 * A PHP wrapper for the OMDb API
 */

class MovieSearch 
{
  /**
   * API URL
   * @var string
   */
  protected $apiUrl = 'http://www.omdbapi.com/';

  /**
   * Movie Title
   * @var array
   */
  protected $movieTitle;

  /**
   * Unfiltered API results
   * @var array
   */
  private $unfilteredResults;

  /**
   * Movie Title
   * @var string
   */
  public $title;

  /**
   * Movie Year
   * @var string
   */
  public $year;

  /**
   * Movie Poster
   * @var string
   */
  public $poster;

  /**
   * All returned data
   * @var array
   */
  public $all;

  /**
   * Class Constructor
   *
   * Make API call, parse information
   *
   * @param string $movieTitle
   * 
   */
  public function __construct($movieTitle)
  {
    // Set Movie Title
    $this->movieTitle = $movieTitle;

    // Make API request
    $this->unfilteredResults = $this->makeRequest();

    // Parse Results
    $this->parseResults();
  }

  /**
   * Make the API URL
   * @return string $url API url
   */
  private function makeApiUrl()
  {
    $url     = $this->apiUrl . '?';

    // Using movie title.
    if($this->movieTitle)
    {
      $type = 't=';
    }

    // Build final URL
    $url = $url . $type . urlencode($this->movieTitle); 

    return $url;
  }

  /**
   * Make simple cURL request.
   * 
   * @return object $result The movie's information
   */
  private function makeRequest()
  {   
    // cURL Resource
    $ch = curl_init();

    // cURL Options
    $curlOptions = [];

    // Parameters
    $params = [
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_URL => $this->makeApiUrl(),
    ];

    // Set Parameters
    $curlOptions = $params;

    // Set Options
    curl_setopt_array($ch, $curlOptions);

    // Execute Call
    $result = curl_exec($ch);
    
    // Close
    curl_close($ch);

    return json_decode($result);
  }

  /**
   * Parse API Results
   * @return
   */
  private function parseResults()
  {
    // Array of keys to make entirely lowercase
    $makeLowercase = ['DVD'];

    foreach($this->unfilteredResults as $key=>$value)
    {
      if(in_array($key, $makeLowercase))
      {
        $key = strtolower($key);
      }
      else
      {
        // Make camel case
        $key = lcfirst($key);       
      }

      // Set class property
      $this->$key = $value;

      // Add to "all" array
      $this->all[$key] = $value;
    }
  }

  /**
   * Get Multple Properties
   * 
   * @param  array  $keys properties
   * @return array
   */
  public function getMultiple($keys = array())
  {
    // If $keys isn't an array, return.
    if(!is_array($keys)) return;

    $properties = [];

    foreach($keys as $key)
    {
      if(property_exists($this, $key))
      {
        $properties[$key] = $this->$key;
      }
    }

    return $properties;
  }
  
  /**
   * Get Value
   * 
   * @param  string  $key     Requested item
   * @param  boolean $asArray Return the results as an array
   * @return mixed            
   */
  public function get($key, $asArray = false)
  {
    return $asArray ? explode(',', $this->$key) : $this->$key;
  }

  /**
   * Get All Information
   *
   * @param  bool $asJson return data as JSON
   * @return array
   */
  public function getAll($asJson = false)
  {   
    return $asJson ? json_encode($this->all) : $this->all;
  }


}