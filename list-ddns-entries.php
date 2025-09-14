<!DOCTYPE html>
<?php
require('./side-menu.php');

#get DDNS list 
$query = "SELECT * FROM ddns";
$result = $pdo->query( $query );
$active_ddns = $result->fetchAll( PDO::FETCH_ASSOC );
$no_rows = $pdo->query("SELECT COUNT(*) as count FROM ddns");
$no_items = $no_rows->fetchColumn();
$counter = 0;

#show table headers
?>
<table border="1">
	<tr>
		<th>domain name(s)</th>
		<th>Active</th>
	</tr>
<?php

if ($counter <= $no_items) {
	foreach ( $active_ddns as $row ) {
		//print_r($row);
		echo '<tr>';
		echo "<td>"; 
		echo '<a href="edit-ddns-entry.php?key='.$row['ID'].'">';
		echo $row['name'];
		echo "</a></td>";
		if ($row['active'] == 'yes') {
			echo '<a href="edit-ddns-entry.php?key='.$row['ID'].'">';
			echo "<td>&check;</a></td>"; 
		}
		if ($row['active'] == 'no'){
			echo '<a href="edit-ddns-entry.php?key='.$row['ID'].'">';
			echo "<td>&nbsp;</a></td>";
		}
		//echo "<td>".$row['active']."</td>";
		echo "</tr>";
	}
}
?>
</table>