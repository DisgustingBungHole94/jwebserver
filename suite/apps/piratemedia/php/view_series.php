<?php
    require_once 'tmdb.php';

    function view_series($id) {
        $request = 'tv/' . $id;
        
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
        
        if (!isset($result->{'number_of_seasons'}) || gettype($result->{'number_of_seasons'}) != 'integer') {
            echo 'Error: Invalid response from TMDB API.';
            return;
        }
        
        $seasons = $result->{'number_of_seasons'};
        if ($seasons < 1) {
            echo 'Error: Invalid response from TMDB API.';
            return;
        }
        
        for ($i = 1; $i < $seasons + 1; $i++) {
            $request = 'tv/' . $id . '/season/' . $i;
            
            tmdb_api_request($request, $result, $result_error);
            
            tmdb_parse_request_error($result_error, $error, $message);
            if ($error == true) {
                continue;
            }
            
            if (!isset($result->{'episodes'}) || gettype($result->{'episodes'}) != 'array') {
                continue;
            }
            
            echo '<span class="season-title">Season '. $i . '</span>';
            echo '<div class="season">';
            
            foreach($result->{'episodes'} as $episode) {
                //echo 'EPISODE ' . $episode->{'episode_number'};
                //echo '<br />Title: ' . $episode->{'name'};
                echo '<div class="item"><a href="watch.php?id=' . $id . '&type=EPISODE&season=' . $i . '&episode=' . $episode->{'episode_number'} . '">' . $episode->{'episode_number'} . ': ' . $episode->{'name'} . '</a></div>';
                //echo '<br /><br />';
            }
            
            echo '</div>';
        }
    }
?>