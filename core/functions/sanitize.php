<?php

// Function to get rid of HTML entities
function escape($string) {
	return htmlentities($string, ENT_QUOTES, 'UTF-8');
}


?>