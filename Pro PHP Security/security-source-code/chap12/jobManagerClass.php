<?php

class jobManager {
public $id; // the record id in the jobs db
public $request; // request to processor api
public $created; // mysql datetime of insertion in the queue
public $started; // mysql datetime of start of processing
public $finished; // mysql datetime of end of processing
public $data; // optional data to be used in carrying out request
public $result; // response to request
public $status; // status of the job: new, running, or done

private $db; // database handle - an open mysql/mysqli database


// constructor assigns db handle
public function __construct( $db ) {
  if ( get_class( $db ) != 'mysqli' ) {
    throw new Exception( "\$db passed to constructor
                         is not a mysqli object." );
  }
  $this->db = $db;
}

// insert() inserts the job into the queue
public function insert() {
  if ( empty( $this->request ) ) {
    throw new Exception( "Will not insert job with empty request." );
  }
  $query = "INSERT INTO jobs SET id='',
              request='{$this->esc($this->request)}',
              created=now(),
              data='{$this->esc($this->data)}',
              status='new' ";
  $result = $this->db->query( $query );
  if ( !$result ) {
    throw new Exception( "Unable to insert job using query $query
                          -- " . $this->db->error() );
  }
  // get id of inserted record
  $this->id = $this->db->insert_id;

  // load job back from database (to get created date)
  $this->load();

  return TRUE;
}


// load() method loads the job with $this->id from the database
public function load() {
  // id must be numeric
  if ( !is_numeric( $this->id ) ) {
    throw new Exception( "Job ID must be a number." );
  }

  // build and perform SELECT query
  $query = "SELECT * FROM jobs WHERE id='$this->id' ";
  $result = $this->db->query( $query );

  if ( !$result ) throw new Exception( "Job #$this->id does not exist." );

  // convert row array into job object
  $row = $result->fetch_assoc();
  foreach( $row AS $key=>$value ) {
    $this->{$key} = $value;
  }

  return TRUE;
}


// next() method finds and loads the next unstarted job
public function next() {
  // build and perform SELECT query
  $query = "SELECT * FROM jobs WHERE status='new'
            ORDER BY created ASC LIMIT 1";
  $result = $this->db->query( $query );

  if ( !$result ) {
    throw new Exception( "Error on query $query
                          -- " . $this->db->error() );
  }

  // fetch row, return FALSE if no rows found
  $row = $result->fetch_assoc();
  if ( empty( $row ) ) return FALSE;

  // load row into job object
  foreach( $row AS $key=>$value ) {
    $this->{$key} = $value;
  }

  return $this->id;
}


// start() method marks a job as being in progress
public function start() {
  // id must be numeric
  if ( !is_numeric( $this->id ) ) {
    throw new Exception( "Job ID must be a number." );
  }

  // build and perform UPDATE query
  $query = "UPDATE jobs SET started=now(), status='running'
            WHERE id='$this->id' ";
  $result = $this->db->query( $query );

  if ( !$result ) {
    throw new Exception( "Unable to update job using query $query
                          -- " . $this->db->error() );
  }

  // load record back from db to get updated fields
  $this->load();

  return TRUE;
}


// finish() method marks a job as completed
public function finish( $status='done' ) {
  // id must be numeric
  if ( !is_numeric( $this->id ) )
    throw new Exception( "Job ID must be a number." );

  // build and perform UPDATE query
  $query = "UPDATE jobs
            SET finished=now(),
                result='{$this->esc($this->result)}',
                status='{$this->esc($status)}'
            WHERE id='$this->id' ";
  $result = $this->db->query( $query );

  if ( !$result ) {
    throw new Exception( "Unable to update job using query $query
                          -- " . $this->db->error() );

  // load record back from db to get updated fields
  $this->load();

  return TRUE;
}


// esc() utility escapes a string for use in a database query
public function esc( $string ) {
  return $this->db->real_escape_string( $string );
}

// end of jobManager class
}

?>
