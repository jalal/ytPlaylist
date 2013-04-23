<html>
<head>
  <title>Youtube Playlist API test page</title>
</head>
<body>
  <h1>YouTube Playlist API Test</h1>
<?php
$dev_key = "your developer key here";
$playlistId = "the playlist id";

require_once('youtube-playlist.class.php');

$playlist = new YoutubePlaylist(array('devkey' => $dev_key, 'playlistId' => $playlistId));
$playlist->setMaxresults(10);
$videos = $playlist->getVideos();

print '<h3>Videos in playlist: ' . $playlist->getTitle() . '</h3>';
print '<ol>';
foreach ($videos as $vid) {
  print '<li>' . $vid['id'] . ' (' . $vid['title'] . ')</li>' . "\n";
}
print '</ol>';
?>
</body>
</html>
