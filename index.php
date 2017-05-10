<?php

session_start();
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

    }
    catch (Google_ServiceException $e) {
        $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
            htmlspecialchars($e->getMessage()));
    }
    catch (Google_Exception $e) {
        $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
            htmlspecialchars($e->getMessage()));
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Autube</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="Content/bootstrap.css" rel="stylesheet" />
    <link href="Content/bootstrap.min.css" rel="stylesheet" />
    <script src="Scripts/jquery-1.9.1.js"></script>
</head>
<body>
    <!--Navbar-->
    <div class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand">Autube</a>
                <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>



            <div class="navbar-collapse collapse" id="navbar-main">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="http://webpage.pace.edu/dp49837p/portfolio/home.html" target="_blank">About Me</a>
                    </li><?php if ($_SESSION['FBID']):
                                   {?>
                    <li>
                        <a href="logout.php">Logout</a>
                    </li><?php }?><?php else: ?>
                    <li>
                        <a href="fbconfig.php">Login with Facebook</a>
                    </li><?php endif ?>
                </ul>

            </div>
        </div>
    </div>

    <div class="container page-header" id="banner">
        <div class="row">
            <div class="col-lg-8 col-md-7 col-sm-6">
                <h1>Autube</h1>
                <p class="lead">Lorem Ipsum is just a dummy text</p>
            </div>
        </div>
    </div>

    <?php if ($_SESSION['FBID']): ?>
    <div class="container">
        <div class="span4">
            <ul class="nav nav-list">
                <li class="nav-header">Image</li>
                <li>
                    <img src="https://graph.facebook.com/<?php echo $_SESSION['FBID'];?>/picture?width=150&height=150" />
                </li>
                <li class="nav-header">Facebook ID</li>
                <li>
                    <?php echo  $_SESSION['FBID']; ?>
                </li>
                <li class="nav-header">Facebook fullname</li>
                <li>
                    <?php echo $_SESSION['FULLNAME']; ?>
                </li>
                <li class="nav-header">Facebook Email</li>
                <li>
                    <?php echo $_SESSION['EMAIL']; ?>
                </li>
            </ul>
        </div>
    </div>
    <?php endif ?>

    <!--Youtube Search Panel-->
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <form method="GET" class="form-horizontal">
                    <fieldset>
                        <div class="form-group">
                            <label for="q" class="col-lg-2 control-label">Search</label>
                            <div class="col-lg-10">
                                <input class="form-control" type="search" name="q" placeholder="Enter Search Term" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="maxResults" class="col-lg-2 control-label">Max Results</label>
                            <div class="col-lg-10">
                                <input class="form-control" type="number"name="maxResults" min="1" max="50" step="1" value="25" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-10 col-lg-offset-2">
                                <input type="submit" class="btn btn-primary" value="Search"/>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <h3>Videos</h3>
        <ul>
            <?php echo @$videos; ?>
        </ul>
        <h3>Channels</h3>
        <ul>
            <?php echo @$channels; ?>
        </ul>
    </div> 
</body>
</html>



