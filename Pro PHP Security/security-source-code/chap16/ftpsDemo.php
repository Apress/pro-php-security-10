<?php

  header( 'Content-Type: text/plain' );

  $ftpsServer = 'ftps.example.net';
  $ftpsPort = 990;
  $ftpsUsername = 'jexample';
  $ftpsPassword = 'wefpo4302e';

  // make ssl connection
  $ftps = ftp_ssl_connect( $ftpsServer, $ftpsPort );
  if ( !$ftps ) exit( "Could not make FTP-SSL connection to $ftpsServer." );
  print "Successfully connected via FTP-SSL to $ftpsServer.\r\n";

  // log in
  if ( !ftp_login( $ftps, $ftpsUsername, $ftpsPassword ) ) {
    exit( "Unable to log in as $ftpsUsername.\r\n" );
  }
  else {
    print "Logged in as $ftpsUsername.\r\n";
  }

  // carry out FTP commands
  $cwd = ftp_pwd( $ftps );
  print "Current working directory: $cwd\r\n";
  // ...

  // close the connection
  ftp_close( $ftps );

?>