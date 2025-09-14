<?php require('./db.php'); ?>
<style>
body {
}
.sidenav {
	width: 150px;
	float:left;
	height:100%;
	left: 0;
    top: 0;
    bottom: 0;
	}
.sidenav a {
	padding: 6px 8px 6px 16px;
	text-decoration: none;
	font-size: 13px;
	color: #2196F3;
	display: block;
}
.sidenav a:hover {
	color: #064579;
}
.main {
	position:absolute;
	float:left;
	margin-left: 150px; /* Same width as the sidebar + left position in px */
}
 @media screen and (max-height: 450px) {
.sidenav {
	padding-top: 13px;
}
.sidenav a {
	font-size: 13px;
}
</style>
<div class="sidenav">
    <a href = "./index.php">Home</a>
    <a href="./list-ddns-entries.php">List DDNS Entries</a>
    <a href="./new-ddns-entry.php">new DDNS Entry</a>
    <a href="./log-viewer.php"> Log Viewer</a>
</div>

<div class="main">
