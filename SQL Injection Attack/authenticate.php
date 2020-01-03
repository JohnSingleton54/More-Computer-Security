<?php // authenticate.php, works w/ setupusers.php
// "SQL Injection"
// John M. Singleton
// completed F 1/3/2020
// REFERENCES:
// - Learning PHP, MySQL & JavaScript 5e by Robin Nixon pp. 296-299
// - https://www.youtube.com/watch?v=gll5wW0Z7aI, "PHP Security: SQL Injection" on betterphp
// SUMMARY:
// Call up authenticate.php in a browser and enter username: pjones' OR '1' = '1
// and no password. Idk if a hacker could truncate my table users, but this helps me understand
// the threat a little better. Sanitize input!!!
// (I'm using the AMPPS development server.)

require_once '../login_nixon.php';
$conn = new mysqli($hn, $un, $pw, $db);

if($conn->connect_error) die("Fatal Error");

if(isset($_SERVER['PHP_AUTH_USER']) &&
   isset($_SERVER['PHP_AUTH_PW'])){

	// ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** *****
   	$un_temp = $_SERVER['PHP_AUTH_USER'];
    $pw_temp = $_SERVER['PHP_AUTH_PW'];
	//$un_temp = mysql_entities_fix_string($conn, $_SERVER['PHP_AUTH_USER']);
	//$pw_temp = mysql_entities_fix_string($conn, $_SERVER['PHP_AUTH_PW']);
	// ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** *****

	$query   = "SELECT * FROM users WHERE username='$un_temp'";
	echo "<br>query string: " . $query;
	$result = $conn->query($query);
	echo "<br># of rows returned: " . $result->num_rows . "<br>";

	if(!$result) die("User not found");
	elseif($result->num_rows){
		$row = $result->fetch_array(MYSQLI_NUM);

		$result->close();

		if(password_verify($pw_temp, $row[3])){
			echo htmlspecialchars("$row[0] $row[1] :
				 Hi $row[0], you are now logged in as '$row[2]'");
		}
		else{die("Invalid username/password combination");}
	}
	else{die("Invalid username/password combination");}
	}
else{
	header('WWW-Authenticate: Basic realm="Restricted Area"');
	header('HTTP/1.0 401 Unauthorized');
	die("Please enter your username and password");
}

$conn->close();
    
function mysql_entities_fix_string($conn, $string){
	return htmlentities(mysql_fix_string($conn, $string));
}

function mysql_fix_string($conn, $string){
	if(get_magic_quotes_gpc()){$string = stripslashes($string);}
	return $conn->real_escape_string($string);
}

?>

