<!doctype html>
<?php
require( './side-menu.php' );
#show log headers
?>
<table border="2">
  <tr>
    <th>Date and Time</th>
    <th>Domain name(s)</th>
    <th>Notes</th>
  </tr>
  <?php
    
    #get latest 500 log entries and display log entries in a table
  $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
  try {
    $query = "SELECT ddns.name, Update_events.DDNS_ID, Update_events.date, Update_events.Time, Update_events.unix_ts, Update_events.msg FROM Update_events LEFT JOIN ddns ON Update_events.DDNS_ID = ddns.ID ORDER BY Update_events.unix_ts DESC Limit 500";
    $result = $pdo->query( $query );
    $logs = $result->fetchAll( PDO::FETCH_ASSOC );
    $counter = 0;
    foreach ( $logs as $entry ) {
      //print_r($entry);
      echo "<tr>";
      echo "<td>" . $entry[ 'date' ] . " @ " . $entry[ 'Time' ] . "</td>";
      echo "<td>" . $entry[ 'name' ] . "</td>";
      echo "<td>" . $entry[ 'msg' ] . "</td>";
      echo "</tr>";
    }
  } catch ( PDOException $e ) {
    die( "Error: " . $e->getMessage() );}
    ?>
</table>
