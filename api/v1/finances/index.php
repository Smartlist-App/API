<?php
include("../../../dashboard/cred.php");
function prettyPrint( $json )
{
    $result = '';
    $level = 0;
    $in_quotes = false;
    $in_escape = false;
    $ends_line_level = NULL;
    $json_length = strlen( $json );

    for( $i = 0; $i < $json_length; $i++ ) {
        $char = $json[$i];
        $new_line_level = NULL;
        $post = "";
        if( $ends_line_level !== NULL ) {
            $new_line_level = $ends_line_level;
            $ends_line_level = NULL;
        }
        if ( $in_escape ) {
            $in_escape = false;
        } else if( $char === '"' ) {
            $in_quotes = !$in_quotes;
        } else if( ! $in_quotes ) {
            switch( $char ) {
                case '}': case ']':
                    $level--;
                    $ends_line_level = NULL;
                    $new_line_level = $level;
                    break;

                case '{': case '[':
                    $level++;
                case ',':
                    $ends_line_level = $level;
                    break;

                case ':':
                    $post = " ";
                    break;

                case " ": case "\t": case "\n": case "\r":
                    $char = "";
                    $ends_line_level = $new_line_level;
                    $new_line_level = NULL;
                    break;
            }
        } else if ( $char === '\\' ) {
            $in_escape = true;
        }
        if( $new_line_level !== NULL ) {
            $result .= "\n".str_repeat( "\t", $new_line_level );
        }
        $result .= $char.$post;
    }

    return $result;
}
$dbname = "";
if($_SERVER["REQUEST_METHOD"] == "POST") {
  $key = str_replace("Bearer ", "", $_SERVER['HTTP_AUTHORIZATION']);
  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM api_keys WHERE apiKey=".json_encode($key));
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $d = $stmt->fetchAll();
    if($stmt->rowCount() == 1) {
      include("../ratelimit.php");
      $dbname = "bcxkspna_test";

      $conn1 = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $stmt1 = $conn1->prepare("SELECT * FROM bm");
      $stmt1->execute();
      $result1 = $stmt1->setFetchMode(PDO::FETCH_ASSOC);
      $res = $stmt1->fetchAll();
      $e = 0;
      $s = array();
      $gs = $cs = $b = $ed = $en = $hi = $ot = $ho = 0;
      foreach ($res as $d) {
        $e += decrypt($d['qty']);
        switch(decrypt($d['price'])) {
          case "Grocery Shopping": $gs += intval(decrypt($d['qty'])); break;
          case "Clothes Shopping": $cs += intval(decrypt($d['qty'])); break;
          case "Bills": $b += intval(decrypt($d['qty'])); break;
          case "Education": $ed += intval(decrypt($d['qty'])); break;
          case "Entertainment": $en += intval(decrypt($d['qty'])); break;
          case "Home Improvement": $hi += intval(decrypt($d['qty'])); break;
          case "Other": $ot += intval(decrypt($d['qty'])); break;
          case "Holidays": $ho += intval(decrypt($d['qty'])); break;
        }
      }
      echo prettyPrint('{"success": true, "message": "", "count": '.$stmt1->rowCount().', "totalSpent": '.$e.', "breakdown": {
      "groceryShopping": '.$gs.',
      "clothesShopping": '.$cs.',
      "bills": '.$b.',
      "education": '.$ed.',
      "entertainment": '.$en.',
      "homeImprovement": '.$hi.',
      "other": '.$ot.',
      "holidays": '.$ho.',
      } }');

    }
    else {
      echo '{"success": false, "message": "API doesn\'t exist :(  - Make sure your API token is valid, and you are using Bearer to authorize your token"}';
    }
  } catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
  $conn = null;
}
else {
  echo '{"success": false, "message": "Must use POST for API"}';
}
?>