<?php

  header( 'Content-Type: text/plain' );

  $tlsUri = 'https://localhost/index.html';
  $openTimeout = 5;
  $socketTimeout = 10;

  // parse uri
  $uri = parse_url( $tlsUri );

  // open socket stream
  $stream = fsockopen( "tls://$uri[host]", 443, $errno, $errstr, $openTimeout );
  if ( !$stream ) exit( "Could not open $tlsUri -- $errstr" );
  print "Successfully opened $tlsUri, results are shown below.\r\n\r\n";

  // set read timeout
  stream_set_timeout( $stream, $socketTimeout );

  // construct and send request
  $request = "GET $uri[path] HTTP/1.0\r\n";
  $request .= "Host: $uri[host]\r\n";
  $request .= "Connection: close\r\n";
  $request .= "\r\n";
  fwrite( $stream, $request );

  print "Response:\r\n";
  // get response
  $response = stream_get_contents( $stream );
  print_r( $response );
  print "\r\n";

  print "Metadata:\r\n";
  // get meta_data
  $meta_data = stream_get_meta_data( $stream );
  print_r( $meta_data );

  // check for timeout
  if ( $meta_data['timed_out'] ) {
    print "Warning: The socket has timed out... \r\n";
  }

  // free the stream
  fclose( $stream );

?>