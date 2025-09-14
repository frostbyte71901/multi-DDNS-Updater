#!/usr/bin/php
<?php
#this file is meant to be either run from the command line or from cron

$realIP = file_get_contents( "http://ipecho.net/plain" );
require( 'db.php' );
$query = "SELECT ID, name, URL FROM ddns WHERE ddns.active = 'yes'";
$result = $pdo->query( $query );
$active_ddns = $result->fetchAll( PDO::FETCH_ASSOC );
$counter = 0;
foreach ( $active_ddns as $row ) {
    #check for multiple dns one URL
    if ( strpos( $row[ 'name' ], "," ) !== false ) {
        $data_array = explode( ",", $row[ 'name' ] );
        foreach ( $data_array as $data ) {
            #google DNS lookup start
            $dns = '8.8.8.8'; // Google Public DNS
            $host = $row[ 'name' ];
            $ip = `nslookup $host $dns`; // Execute command in shell
            $ips = array();
            if ( preg_match_all( '/Address: ((?:\d{1,3}\.){3}\d{1,3})/', $ip, $match ) > 0 ) {
                $ips = $match[ 1 ];
            }
            $ext_ip = $ips[ '0' ];
            #google DNS loopup end
            if ( $ext_ip != $realIP ) {
                $ch = curl_init(); // Initialize cURL session
                curl_setopt( $ch, CURLOPT_URL, $row[ 'URL' ] ); // Set the URL
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // Return the transfer as a string


                //insert log update
                $statement = $pdo->prepare( 'INSERT INTO "Update_events" ("DDNS_ID", "date", "Time", "unix_ts", "msg") VALUES (:DDNS_ID, :date, :Time, :unix_ts, :msg)' );
                $statement->bindValue( ':DDNS_ID', $row[ 'ID' ] );
                $statement->bindValue( ':date', date( 'd-m-Y' ) );
                $statement->bindValue( ':Time', date( 'H:i:s' ) );
                $statement->bindValue( ':unix_ts', time() );
                $statement->bindValue( ':msg', $host . " Checked and IP address has not changed." );
                $statement->execute(); // you can reuse the statement with different values
                //end log update


                $counter += 1;
                if ( $counter % 2 == 0 ) {
                    sleep( 90 );
                }
            }
            if ( $ext_ip == $realIP ) {
                //insert log update
                $statement = $pdo->prepare( 'INSERT INTO "Update_events" ("DDNS_ID", "date", "Time", "unix_ts", "msg") VALUES (:DDNS_ID, :date, :Time, :unix_ts, :msg)' );
                $statement->bindValue( ':DDNS_ID', $row[ 'ID' ] );
                $statement->bindValue( ':date', date( 'd-m-Y' ) );
                $statement->bindValue( ':Time', date( 'H:i:s' ) );
                $statement->bindValue( ':unix_ts', time() );
                $statement->bindValue( ':msg', $host . " updated to " . $realIP );
                $statement->execute(); // you can reuse the statement with different values
                //end log update
            }
        }
    }
    #end check for multiple DNS one URL
    else {

        #google DNS lookup start
        $dns = '8.8.8.8'; // Google Public DNS
        $host = $row[ 'name' ];
        $ip = `nslookup $host $dns`; // Execute command in shell
        $ips = array();
        if ( preg_match_all( '/Address: ((?:\d{1,3}\.){3}\d{1,3})/', $ip, $match ) > 0 ) {
            $ips = $match[ 1 ];
        }
        $ext_ip = $ips[ '0' ];
        #google DNS loopup end
        if ( $ext_ip != $realIP ) {
            $ch = curl_init(); // Initialize cURL session
            curl_setopt( $ch, CURLOPT_URL, $row[ 'URL' ] ); // Set the URL
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // Return the transfer as a string

            //insert log update
            $statement = $pdo->prepare( 'INSERT INTO "Update_events" ("DDNS_ID", "date", "Time", "unix_ts", "msg") VALUES (:DDNS_ID, :date, :Time, :unix_ts, :msg)' );
            $statement->bindValue( ':DDNS_ID', $row[ 'ID' ] );
            $statement->bindValue( ':date', date( 'd-m-Y' ) );
            $statement->bindValue( ':Time', date( 'H:i:s' ) );
            $statement->bindValue( ':unix_ts', time() );
            $statement->bindValue( ':msg', $host . " updated to " . $realIP );
            $statement->execute(); // you can reuse the statement with different values
            //end log update

            $counter += 1;
            if ( $counter % 2 == 0 ) {
                sleep( 90 );
            }
            #print( $row[ 'name' ] . PHP_EOL );
            #print( "external dns record" . $ext_ip . PHP_EOL );
            #print( "current ip address" . $realIP . PHP_EOL . PHP_EOL );
            $records = null;
        }
        if ( $ext_ip == $realIP ) {

            $statement = $pdo->prepare( 'INSERT INTO "Update_events" ("DDNS_ID", "date", "Time", "unix_ts", "msg") VALUES (:DDNS_ID, :date, :Time, :unix_ts, :msg)' );
            $statement->bindValue( ':DDNS_ID', $row[ 'ID' ] );
            $statement->bindValue( ':date', date( 'd-m-Y' ) );
            $statement->bindValue( ':Time', date( 'H:i:s' ) );
            $statement->bindValue( ':unix_ts', time() );
            $statement->bindValue( ':msg', $host . " Checked and IP address has not changed." );
            $statement->execute(); // you can reuse the statement with different values
            //end log update			

        }
    }
}
?>
