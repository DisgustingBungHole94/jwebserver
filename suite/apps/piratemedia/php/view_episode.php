<?php
    require_once 'videospider.php';

    function view_episode($id, $season, $episode) {
        $ticket = '';
        $error = true;
        
        generate_ticket($id, $season, $ticket, $error);
        if ($error == true) {
            echo 'Failed to generate VideoSpider ticket.';
            return;
        }
        
        get_episode($id, $season, $episode, $ticket);
    }
?>