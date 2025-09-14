<!doctype html>
<?php
//require( 'db.php' );
require( './side-menu.php' );

# if post dns_name_s is set, attempt to insert new DDNS record and also run the DDNS URL for the first time
if (isset($_POST['dns_name_s'])) {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $names = $_POST['dns_name_s'];
	$url = $_POST['url'];
	$active = $_POST['active'];
    try {
        $new = $pdo->prepare( 'INSERT INTO "ddns" ("DDNS_ID", "URL", "active") VALUES (:dns, :address, :upd)' );
        $new -> bindValue(':dns', $names);
        $new -> bindValue(':address', $url);
        $new -> bindValue(':upd', $active);
        $new -> execute();
    }
    catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
    $ch = curl_init(); // Initialize cURL session
    curl_setopt( $ch, CURLOPT_URL, $url ); // Set the URL
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // Return the transfer as a string

}

#show new DDNS entry form
?>
<div>
<form method="post">
  <div>
    <label for="dns_name_s">DNS Name(s)</label>
    <textarea id="dns_name_s" name="dns_name_s" required></textarea>
  </div>
  <div>
    <label for="url">URL</label>
    <textarea id="url" name="url"></textarea>
  </div>
  <div>
	  
    <label for="active">Active</label>
    <select id="active" name="active" required>
      <option value="yes">Yes</option>
      <option value="no">No</option>
    </select>
  </div>
  <button type="submit">Submit</button>
</form>
</div>