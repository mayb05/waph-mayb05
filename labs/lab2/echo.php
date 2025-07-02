<?php
	if (isset($_REQUEST["data"])) {
	echo htmlentities($_REQUEST["data"], ENT_QUOTES, 'UTF-8');
	}
?>
