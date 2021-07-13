<?php
    function generate_ticket($id, $season, &$ticket, &$error) {
        require 'key.php';
		
        if ($_SERVER['REMOTE_ADDR'] == '::1') {
            $ip_api_result = @file_get_contents('https://api6.ipify.org/?format=json');
            $ip_api_json = json_decode($ip_api_result);
            $ip = $ip_api_json->{'ip'};
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        $vs_api_request = 'https://videospider.in/getticket.php?key=' . $vs_api_key . '&secret_key=' . $vs_secret_key . '&video_id=' . $id . '&s=' . $season . '&ip=' . $ip;
        
        $vs_api_result = @file_get_contents($vs_api_request);
        if ($vs_api_result == false) {
            $error = true;
            return;
        }
        
        $ticket = $vs_api_result;
        $error = false;
        
        return;
    }

    function get_episode($id, $season, $episode, $ticket) {
        global $vs_api_key;
        
        $vs_api_src = 'https://videospider.stream/getvideo?key='. $vs_api_key . '&video_id=' . $id . '&tv=1&s=' . $season . '&e=' . $episode . '&tmdb=1&ticket=' . $ticket;
        
        echo '<iframe id="frame" src="' . $vs_api_src . '" width="600" height="400" frameborder="0" allowfullscreen="true" scrolling="yes"></iframe>';
    }

    function get_movie($id, $ticket) {
        global $vs_api_key;
        
        $vs_api_src = 'https://videospider.stream/getvideo?key=' . $vs_api_key . '&video_id=' . $id . '&tmdb=1&ticket=' . $ticket;
        
        echo '<iframe id="frame" src="' . $vs_api_src . '" width="600" height="400" frameborder="0" allowfullscreen="true" scrolling="yes"></iframe>';
    }
?>