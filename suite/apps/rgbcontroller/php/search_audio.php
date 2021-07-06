<html>
    <head>
        <link rel="stylesheet" href="../css/search_audio.css?<?php echo time(); ?>" />
    </head>
    <body>
        <div class="content">
            <div class="cell">
                <span class="video-title">Please select a video. It might take a minute for it to download.</span>
            </div>
            <?php
                session_start();

                if (!isset($_POST['q'])) {
                    $_SESSION['serverError'] = 'No search query was specified!';
                    echo '<script>top.window.location.href="../index.php";</script>';
                    exit();
                }

                $requestUrl = 'https://www.googleapis.com/youtube/v3/search?part=snippet&order=viewCount&q=' . urlencode($_POST['q']) .'&type=video&videoDefinition=high&key=AIzaSyAo4PuxV2K0mdlyBgRQKinX55r4jCPHDro';
                $request = file_get_contents($requestUrl);

                if (!$request) {
                    $_SESSION['serverError'] = 'Failed to connect to YouTube API! Please try again later.';
                    echo '<script>top.window.location.href="../index.php";</script>';
					exit();
                }

                $response = json_decode($request, true);
                if (!$response) {
                    $_SESSION['serverError'] = 'An unexpected error occurred! Please try again later.';
                    echo '<script>top.window.location.href="../index.php";</script>';
                    exit();
                }

                if (!$response['pageInfo'] || !$response['pageInfo']['totalResults'] || !$response['items']) {
                    $_SESSION['serverError'] = 'Received an invalid response from the YouTube API!';
                    echo '<script>top.window.location.href="../index.php";</script>';
                    exit();
                }
            
                foreach($response['items'] as $item) {
                    if ($item['kind'] !== 'youtube#searchResult') {
                        continue;
                    }

                    $videoName = html_entity_decode($item['snippet']['title']);
                    $videoName = preg_replace('/[^\da-z ]/i', '', $videoName);
                    if ($videoName == '') {
                        $videoName = 'YouTube Audio';
                    }
                    
                    echo '<div class="row"><div class="cell">';
                    echo '<img class="video-thumbnail" src="' . $item['snippet']['thumbnails']['medium']['url'] . '" />';
                    echo '<a class="video-title" href="download_audio.php?id=' . $item['id']['videoId'] . '&name=' . urlencode($videoName) . '">' . $item['snippet']['title'] . '</span>';
                    echo '</div></div>';
                }
            ?>
        </div>
    </body>
</html>