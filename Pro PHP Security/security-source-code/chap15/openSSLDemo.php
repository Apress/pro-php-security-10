<?php

  // create a new openSSL object
  include_once( 'openSSL.php' );
  $openSSL = new openSSL;

  // generate a keypair
  $passphrase = 'This is a passphrase of reasonable length.';

  // a "Distinguished Name" is required for the public key
  $distinguishedName = array(
    "countryName" => "US",
    "stateOrProvinceName" => "New York",
    "localityName" => "New York City",
    "organizationName" => "example.net",
    "organizationalUnitName" => "Pro PHP Security",
    "commonName" => "pps.example.net",
    "emailAddress" => "csnyder@example.net"
    );
  $openSSL->makeKeys( $distinguishedName, $passphrase );
  $private = $openSSL->privateKey();
  $public = $openSSL->certificate();

  print "<h3>Key and Certificate Generation</h3>";
  print "<p>Your certificate belongs to:<br />" . $openSSL->getCommonName() . "</p>";
  print "<p>Distinguished Name:<br /><pre>" . print_r($openSSL->getDN(),1) . "</pre></p>";
  print "<p>Your private key is:<br /><pre>$private</pre></p>";
  print "<p>Your public key is:<br /><pre>$public</pre></p>";
  print "<p>Your certificate is signed by:<br />" . $openSSL->getCACommonName() . "</p>";
  print "<p>CA Distinguished Name:<br /><pre>";
  print_r($openSSL->getCA(),1) . "</pre></p>";
  print "<hr />";

  // encrypt some text using the public key
  $text = "The goat is in the red barn.";
  $encrypted = $openSSL->encrypt( $text, $public );
  print "<h3>Ecncryption</h3>";
  print "<p>Plain text was:<br />$text</p>";
  print "<p>And encrypted text is:<br /><pre>$encrypted</pre></p>";

  // decrypt it using the private key
  $decrypted = $openSSL->decrypt( $encrypted, $passphrase );
  print "<p>Decrypted with Private Key:<br />$decrypted</p>";

  // sign some message using the private key
  $message = "So long, and thanks for all the fish.";
  $signed = $openSSL->sign( $message, $passphrase );
  print "<h3>Signing</h3>";
  print "<p>Signed using Private Key:<br /><pre>$signed</pre></p>";

  // verify signature
  $verified = $openSSL->verify( $signed, $public );
  print "<p>Verifying signature using Certificate:<br />";
  if ( $verified ) {
    print "  ...passed ($verified).</p>";
  }
  else {
    print "  ...failed.</p>";
  }

?>