<!doctype html>
<?php
//require( 'db.php' );
require( './side-menu.php' );


#if post id is set - run update
if (isset($_POST['id'])) {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	echo "updated ";
	echo "<br>";
	$id = $_POST['id'];
	$names = $_POST['dns_name_s'];
	$url = $_POST['url'];
	$active = $_POST['active'];
    
    #attempt to update ddns entry
    try {
        $sql = 'UPDATE ddns SET name = :dns, URL = :address, active = :upd  WHERE ID = :di';
        $update = $pdo->prepare($sql);
        $update -> bindValue(':dns', $names);
        $update -> bindValue(':address', $url);
        $update -> bindValue(':upd', $active);
        $update -> bindValue(':di', $id);
        $update -> execute();
    }
    catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}


#sql query for form and display form
    if ( !isset( $_GET[ 'key' ] ) || $_GET[ 'key' ] != 0 ) {
        $ddns_id = $_GET[ 'key' ];
        $query = 'SELECT * FROM `ddns` WHERE `id` = ' . $ddns_id;
        $result = $pdo->query( $query );
        $active_ddns = $result->fetchAll( PDO::FETCH_ASSOC );
        foreach ( $active_ddns as $row ) {
            //print_r( $row );
            $active = $row[ 'active' ];
            ?>
<div>
<form method="post">
  <div>
    <label for="id">ID: <?php echo $row['ID'];?></label>
    <input type="hidden" id="id" name="id" required value="<?php echo $row['ID'];?>">
  </div>
  <div>
    <label for="dns_name_s">DNS Name(s)</label>
    <textarea id="dns_name_s" name="dns_name_s" required><?php echo $row['name'];?></textarea>
  </div>
  <div>
    <label for="url">URL</label>
    <textarea id="url" name="url"><?php echo $row['URL'];?></textarea>
  </div>
  <div>
	  
    <label for="active">Active</label>
    <select id="active" name="active" required>
      <option value="yes" <?php if ($active == "yes"){echo "selected";}?> >Yes</option>
      <option value="no" <?php if ($active == "no"){echo "selected";}?> >No</option>
    </select>
  </div>
  <button type="submit">Submit</button>
</form>
</div>
<?php
}
}
?>
