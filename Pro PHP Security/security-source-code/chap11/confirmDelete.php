<?php

session_start();

// first time through, no confirmation yet
if ( empty( $_POST['confirmationKey'] ) ) {
  // check for commentID
  if ( empty( $_REQUEST['commentID'] ) ) {
    exit("This action requires a comment id.");
  }

  // comment to be deleted (may be GET or POST)
  $commentID = $_REQUEST['commentID'];

  // generate confirmation key
  $confirmationKey = uniqid( rand(), TRUE );

  // save confirmation key
  $_SESSION['confirmationKey'] = $confirmationKey;

  // render form
  ?>
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>confirm delete</title>
  </head>
  <body>
  <h1>Please confirm deletion of comment #<?=$commentID?></h1>
  <form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="post">
    <input type="hidden" name="confirmationKey"
      value="<?= $confirmationKey ?>" />
    <input type="hidden" name="commentID" value="<?= $commentID ?>" />
    <input type="submit" value="Confirmed" />
    &nbsp;&nbsp;
    <input type="button" value="cancel" onclick="window.location='./';" />
  </form>
  </body>
  </html>
  <?
  exit();
}
elseif ( $_POST['confirmationKey'] != $_SESSION['confirmationKey'] ) {
  exit( 'Could not confirm deletion. Please contact an administrator.' );
}

// confirmed; continue...
print "Deleting comment #$commentID now.";

?>