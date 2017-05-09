<?php
if (@$_GET['q'] && @$_GET['maxResults']) {
    // Call set_include_path() as needed to point to your client library.
    require_once ($_SERVER["DOCUMENT_ROOT"].'/src/Google_Client.php');
    require_once ($_SERVER["DOCUMENT_ROOT"].'/src/Google_Service.php');

    /* Set $DEVELOPER_KEY to the "API key" value from the "Access" tab of the
    Google APIs Console <http://code.google.com/apis/console#access>
    Please ensure that you have enabled the YouTube Data API for your project. */
    $DEVELOPER_KEY = 'AIzaSyCP5FGEHUGHF3A7aoe6C0LQ2o3HG1KnkmQ';

    $client = new Google_Client();
    $client->setDeveloperKey($DEVELOPER_KEY);

    $youtube = new Google_YoutubeService($client);

    try {
        $searchResponse = $youtube->search->listSearch('id,snippet', array(
            'q' => $_GET['q'],
            'maxResults' => $_GET['maxResults'],
        ));

        $videos = '';
        $channels = '';

        foreach ($searchResponse['items'] as $searchResult) {
            switch ($searchResult['id']['kind']) {
                case 'youtube#video':
                    $videos .= sprintf('<li>%s (%s)</li>', $searchResult['snippet']['title'],
                        $searchResult['id']['videoId']."<a href=http://www.youtube.com/watch?v=".$searchResult['id']['videoId']." target=_blank>   Watch This Video</a>");
                    break;
                case 'youtube#channel':
                    $channels .= sprintf('<li>%s (%s)</li>', $searchResult['snippet']['title'],
                        $searchResult['id']['channelId']);
                    break;
            }
        }

    } catch (Google_ServiceException $e) {
        $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
            htmlspecialchars($e->getMessage()));
    } catch (Google_Exception $e) {
        $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
            htmlspecialchars($e->getMessage()));
    }
}

?>

<!doctype html>
<html>
<head>
    <title>YouTube Search</title>
    <link href="http://www.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet" />
    <style type="text/css">
        body{margin-top: 50px; margin-left: 50px}
    </style>
</head>
<body>
    
<form method="GET">
    <div>
        Search Term: <input type="search" id="q" name="q" placeholder="Enter Search Term">
    </div>
    <div>
        Max Results: <input type="number" id="maxResults" name="maxResults" min="1" max="50" step="1" value="25">
    </div>
    <input type="submit" value="Search">
</form>
<h3>Videos</h3>
<ul><?php echo @$videos; ?></ul>
<h3>Channels</h3>
<ul><?php echo @$channels; ?></ul>
</body>
</html>