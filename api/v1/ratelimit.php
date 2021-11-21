<?php
try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = "UPDATE api_keys SET ratelimit=ratelimit+1 WHERE apiKey=:key";

  // Prepare statement
  $stmt = $conn->prepare($sql);

  // execute the query
  $stmt->execute(array( ":key" => $key));

  // echo a message to say the UPDATE succeeded
} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}
$conn = null;
if($d[0]['ratelimit'] >= 500) {
  exit('{"success": false, "message": "Ratelimit exceeded. 500/500 requests"}');
}
?>