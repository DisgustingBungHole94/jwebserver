<?php
    function view_movie($id) {
        $ticket = '';
        $error = true;
        
        generate_ticket($id, 0, $ticket, $error);
        if ($error == true) {
            echo 'Failed to generate VideoSpider ticket.';
            return;
        }
        
        get_movie($id, $ticket);
    }
?>