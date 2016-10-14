<?php

// confirm form source
$referrer = $_SERVER['HTTP_REFERER'];
if ( !empty( $referrer ) ) {
  $uri = parse_url( $referrer );
  if ( $uri['host'] != $_SERVER['HTTP_HOST'] ) {
    exit( "Form submissions from $referrer not allowed." );
  }
}
else {
  exit( "Referrer not found.
         Please <a href='$_SERVER[SCRIPT_NAME]'>try again</a>." );
}

// continue...

?>