<?php // setupusers.php, works with authenticate.php
// John M. Singleton
// completed F 1/3/2020
// REFERENCE: Learning PHP, MySQL & JavaScript 5e by Robin Nixon pp. 296-299
// See authenticate.php for additional comments.

require_once '../login_nixon.php';
$conn = new mysqli($hn, $un, $pw, $db);

if($conn->connect_error) die("Fatal Error");

$query = "CREATE TABLE users (
  forename VARCHAR(32) NOT NULL,
  surname  VARCHAR(32) NOT NULL,
  username VARCHAR(32) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
)";

$result = $conn->query($query);
if(!$result) die("Fatal Error");

$forename = 'Bill';
$surname  = 'Smith';
$username = 'bsmith';
$password = 'mysecret';
$hash     = password_hash($password, PASSWORD_DEFAULT);

add_user($conn, $forename, $surname, $username, $hash);

$forename = 'Pauline';
$surname  = 'Jones';
$username = 'pjones';
$password = 'acrobat';
$hash     = password_hash($password, PASSWORD_DEFAULT);

add_user($conn, $forename, $surname, $username, $hash);

function add_user($conn, $fn, $sn, $un, $pw){
	$stmt = $conn->prepare('INSERT INTO users VALUES(?,?,?,?)');
	$stmt->bind_param('ssss', $fn, $sn, $un, $pw);
	$stmt->execute();
	$stmt->close();
}

?>

