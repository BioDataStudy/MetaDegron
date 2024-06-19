<?php

function echoalert($_info) {
	echo"<script type='text/javascript'>alert(".$_info.");</script>";
}


function startsWith( $haystack, $needle ) {
     $length = strlen( $needle );
     return substr( $haystack, 0, $length ) === $needle;
}
function endsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    if( !$length ) {
        return true;
    }
    return substr( $haystack, -$length ) === $needle;
}

$python =  "python";
// $python =  "D:/anaconda3/python";

?>