<?php
    if (isset($_REQUEST["data"])) {
	    //changes characters to html entities. 
        echo htmlentities($_REQUEST["data"], ENT_QUOTES, 'UTF-8');
    }
?>
