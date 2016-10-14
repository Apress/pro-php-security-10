<?php

  header( 'Content-Type: text/plain' );

  $httpsUri = 'https://localhost/index.html';

  // create a context
  $options = array( 'http' => array( 'user_agent' => 'sslConnections.php' ),
      'ssl' => array( 'allow_self_signed' => TRUE ) );
  $context = stream_context_create( $options );

  // open a stream via HTTPS
  $stream = @fopen( $httpsUri, 'r', FALSE, $context );
  if ( !$stream ) exit( "Could not open $httpsUri." );
  print "Successfully opened $httpsUri; results are shown below.\r\n\r\n";

  print "Resource:\r\n";
  // get resource
  $resource = stream_get_contents( $stream );
  print_r( $resource );
  print "\r\n";

  print "Metadata:\r\n";
  // look at the metadata
  $metadata = stream_get_meta_data( $stream );
  print_r( $metadata );

  // free the stream
  fclose( $stream );

?>