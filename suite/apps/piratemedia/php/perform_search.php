<?php
    require_once 'tmdb.php';

    function perform_search($q) {
        $request = 'search/multi?query=' . urlencode($_GET["q"]);
        
        $result = null;
        $result_error = -1;

        $error = true;
        $message = '';
        
        tmdb_api_request($request, $result, $result_error);
        
        tmdb_parse_request_error($result_error, $error, $message);
        if ($error == true) {
            echo $message;
            return;
        }
        
        if (!isset($result->{'total_pages'}) || gettype($result->{'total_pages'}) != 'integer') {
            echo 'Error: Invalid response from TMDB API.';
            return;
        }
        
        if ($result->{'total_pages'} <= 0) {
            echo '<span class="no-results">No results!</span>';
            echo '<br /><br />';
            echo '<button class="back" onclick="back()">Back</button>';
            return;
        }
        
        //var_dump($result);
        
        if (!isset($result->{'results'}) || gettype($result->{'results'}) != 'array') {
            echo 'Error: Invalid response from TMDB API.';
            return;
        }
        
        foreach($result->{'results'} as $item) {
            if ($item->{'media_type'} == 'tv') {
                $type = 'SERIES';
                $link = 'series.php?id=' . $item->{'id'};
                $title = $item->{'name'};
            } else if ($item->{'media_type'} == 'movie') {
                $type = 'MOVIE';
                $link = 'watch.php?id=' . $item->{'id'} . '&type=MOVIE';
                $title = $item->{'title'};
            } else {
                continue;
            }
            
            echo '<div class="item">';
            echo '<span class="title">' . $title . '</span>';
            echo '<a href="' . $link . '"><img width=200 height=300 src="http://image.tmdb.org/t/p/w200' . $item->{'poster_path'} . '" /></a>';
            echo '</div>';
            /*echo '<br />Title: ' . $title;
            echo '<br />ID: ' . $item->{'id'};
            echo '<br />Type: ' . $type;
            echo '<br />Link: ' . $link;
            echo '<br /><br />';*/
        }
    }
?>