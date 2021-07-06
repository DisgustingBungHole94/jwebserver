<?php
    function tmdb_api_request($request, &$result, &$error) {
        $tmdb_api_key = "de796dd7e441f98f2cb90d2cebbbc587";
        
        if (strpos($request, '?') !== false) {
            $tmdb_api_request = "https://api.themoviedb.org/3/". $request . '&api_key=' . $tmdb_api_key;
        } else {
            $tmdb_api_request = "https://api.themoviedb.org/3/". $request . '?api_key=' . $tmdb_api_key;
        }
        
        $tmdb_api_result = @file_get_contents($tmdb_api_request);
        if ($tmdb_api_result == false) {
            echo $tmdb_api_result;
            $error = 1;
            return;
        }
        
        $tmdb_api_json = json_decode($tmdb_api_result);
        if ($tmdb_api_json == null) {
            $error = 2;
            return;
        }
        
        $result = $tmdb_api_json;
        $error = 0;
        
        return;
    }
    
    function tmdb_parse_request_error($response, &$error, &$message) {
        if ($response != 0) {
            switch ($response) {
                case 1:
                    $error = true;
                    $message = 'Failed to access TMDB API.';
                    break;
                case 2:
                    $error = true;
                    $message = 'Invalid JSON response from TMDB API.';
                    break;
                default:
                    $error = true;
                    $message = 'Unknown.';
                    break;
            }
        } else {
            $error = false;
            $message = 'No error.';
        }
    }
?>