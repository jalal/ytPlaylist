<?php
/**
 * Copyright 2013 jalal @ gnomedia <thpushman@gmail.com>
 *
 * Licensed under the MIT License,
 * See file LICENSE included with this file.
 *
 */
require_once __DIR__ . '/google-api-php-client/src/Google_Client.php';
require_once __DIR__ . '/google-api-php-client/src/contrib/Google_YouTubeService.php';

/**
* a class to encapsulate access to a youtube playlist
*/
class YoutubePlaylist
{

  private $client;
  private $youtube;
  private $devkey;
  private $playlistId;
  private $maxResults;

  /**
   * construct the YoutubePlaylist
   *
   * @param array $args arguments for Youtube, can also be set by setters
   */
  function __construct($args)
  {
    $this->client = new Google_Client();
    $this->client->setApplicationName('YouTube Application');
    $this->maxResults = 10;
    if (is_array($args)) {
      $this->setDevkey($args['devkey']);
      $this->setPlaylistId ($args['playlistId']);
      $this->youtube = new Google_YoutubeService($this->client);
    } else {
      throw new Exception ('No constuctor arguments found');
    }
  }

  /**
   * get an array of links from this playlist
   *
   * @return array of [videoids, title]
   */
  public function getVideos()
  {
    $ret = array();
    try {
      $searchResponse = $this->youtube->playlistItems->listPlaylistItems('id,snippet',$this->getParams());
      // die(print_r($searchResponse));
      foreach ($searchResponse['items'] as $result) {
        $ret[] = array('id' => $result['snippet']['resourceId']['videoId']
                     , 'title' => $result['snippet']['title']);
      }
    } catch (Google_ServiceException $e) {
      throw $e;
    } catch (Google_Exception $e) {
      throw $e;
    }
    return $ret;
  }

  public function getTitle()
  {
    $title = "Unknown";
    try {
      $list = $this->youtube->playlists->listPlaylists('snippet',array('id' => $this->playlistId));
      // die(print_r($list));
      //
      // assume there is only one playlist
      $title = $list['items'][0]['snippet']['title'];
    } catch (Google_ServiceException $e) {
      throw $e;
    } catch (Google_Exception $e) {
      throw $e;
    }
    return $title;
  }

  /**
   * maximum number of results to return
   *
   * @param integer $value
   */
  public function setMaxResults($value=10)
  {
    $this->maxResults = $value;
  }

  /**
   * the developer key to use for google
   *
   * @param string $key developer key
   */
  public function setDevkey($key='')
  {
    $this->devkey = $key;
    $this->client->setDeveloperKey($key);
  }

  /**
   * the playlist id to be fetched
   *
   * @param string $id playlist id
   */
  public function setPlaylistId($id='')
  {
    $this->playlistId = $id;
  }

  /**
   * the params to be sent when querying the youtube playlist api
   *
   * @return array with playlistId and maxResults
   */
  private function getParams()
  {
    return array('playlistId' => $this->playlistId, 'maxResults' => $this->maxResults);
  }

}
