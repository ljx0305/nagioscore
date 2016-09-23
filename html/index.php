<?php
include_once(dirname(__FILE__).'/includes/utils.inc.php');

$this_version = '4.2.1';
$link_target = 'main';

// Allow specifying main window URL for permalinks, etc.
$url = 'main.php';

if (isset($_GET['corewindow'])) {

	// The default window url may have been overridden with a permalink...
	// Parse the URL and remove permalink option from base.
	$a = parse_url($_GET['corewindow']);

	// Build the base url.
	$url = htmlentities($a['path']).'?';
	$url = (isset($a['host'])) ? $a['scheme'].'://'.$a['host'].$url : '/'.$url;

	$query = isset($a['query']) ? $a['query'] : '';
	$pairs = explode('&', $query);
	foreach ($pairs as $pair) {
		$v = explode('=', $pair);
		if (is_array($v)) {
			$key = urlencode($v[0]);
			$val = urlencode(isset($v[1]) ? $v[1] : '');
			$url .= "&$key=$val";
		}
	}
	if (preg_match("/^http:\/\/|^https:\/\/|^\//", $url) != 1)
		$url = "main.php";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="ROBOTS" content="NOINDEX, NOFOLLOW">

	<link rel="stylesheet" type="text/css" href="stylesheets/common.css?<?php echo $this_version; ?>" />
	<link rel="stylesheet" type="text/css" href="stylesheets/navbar.css?<?php echo $this_version; ?>" />
	<script LANGUAGE="javascript">
		var n = Math.round(Math.random() * 10000000000);
		document.write("<title>Nagios Core on " + window.location.hostname + "</title>");
		document.cookie = "NagFormId=" + n.toString(16);
	</script>
	<link rel="shortcut icon" href="images/favicon.ico" type="image/ico">

	<style>
		div.maind { height:100%; margin-left:175px; overflow:hidden; }
		#main { height:100%; width:100%; }
	</style>

</head>

<body>

<div class="navbar">

	<div class="navbarlogo">
		<a href="https://www.nagios.org" target="_blank"><img src="images/red-sblogo.png" height="39" width="140" border="0" alt="Nagios" /></a>
	</div>

	<div class="navsection">
		<div class="navsectiontitle">General</div>
			<ul>
				<li><a href="main.php" target="<?php echo $link_target;?>">Home</a></li>
				<li><a href="https://go.nagios.com/nagioscore/docs" target="_blank">Documentation</a></li>
				<li><a href="dologout" target="_top">Logout</a></li>
			</ul>
	</div>

	<div class="navsection">
		<div class="navsectiontitle">Current Status</div>
			<ul>
				<li><a href="<?php echo $cfg["cgi_base_url"];?>/tac.cgi" target="<?php echo $link_target;?>">Tactical Overview</a></li>
				<li>
					<a href="map.php?host=all" target="<?php echo $link_target;?>">Map</a>
					<a href="<?php echo $cfg["cgi_base_url"];?>/statusmap.cgi?host=all" target="<?php echo $link_target;?>">(Legacy)</a>
				</li>
				<li><a href="<?php echo $cfg["cgi_base_url"];?>/status.cgi?hostgroup=all&amp;style=hostdetail" target="<?php echo $link_target;?>">Hosts</a></li>
				<li><a href="<?php echo $cfg["cgi_base_url"];?>/status.cgi?host=all" target="<?php echo $link_target;?>">Services</a></li>
				<li>
					<a href="<?php echo $cfg["cgi_base_url"];?>/status.cgi?hostgroup=all&amp;style=overview" target="<?php echo $link_target;?>">Host Groups</a>
					<ul>
						<li><a href="<?php echo $cfg["cgi_base_url"];?>/status.cgi?hostgroup=all&amp;style=summary" target="<?php echo $link_target;?>">Summary</a></li>
						<li><a href="<?php echo $cfg["cgi_base_url"];?>/status.cgi?hostgroup=all&amp;style=grid" target="<?php echo $link_target;?>">Grid</a></li>
					</ul>
				</li>
				<li>
					<a href="<?php echo $cfg["cgi_base_url"];?>/status.cgi?servicegroup=all&amp;style=overview" target="<?php echo $link_target;?>">Service Groups</a>
					<ul>
						<li><a href="<?php echo $cfg["cgi_base_url"];?>/status.cgi?servicegroup=all&amp;style=summary" target="<?php echo $link_target;?>">Summary</a></li>
						<li><a href="<?php echo $cfg["cgi_base_url"];?>/status.cgi?servicegroup=all&amp;style=grid" target="<?php echo $link_target;?>">Grid</a></li>
					</ul>
				</li>
			</ul>

			<ul>
				<li>Problems
					<ul>
						<li><a href="<?php echo $cfg["cgi_base_url"];?>/status.cgi?host=all&amp;servicestatustypes=28" target="<?php echo $link_target;?>">Services</a> (<a href="<?php echo $cfg["cgi_base_url"];?>/status.cgi?host=all&amp;type=detail&amp;hoststatustypes=3&amp;serviceprops=42&amp;servicestatustypes=28" target="<?php echo $link_target;?>">Unhandled</a>)</li>
						<li><a href="<?php echo $cfg["cgi_base_url"];?>/status.cgi?hostgroup=all&amp;style=hostdetail&amp;hoststatustypes=12" target="<?php echo $link_target;?>">Hosts</a> (<a href="<?php echo $cfg["cgi_base_url"];?>/status.cgi?hostgroup=all&amp;style=hostdetail&amp;hoststatustypes=12&amp;hostprops=42" target="<?php echo $link_target;?>">Unhandled</a>)</li>
						<li><a href="<?php echo $cfg["cgi_base_url"];?>/outages.cgi" target="<?php echo $link_target;?>">Network Outages</a></li>
					</ul>
				</li>
			</ul>

		<div class="navbarsearch">
			<form method="get" action="<?php echo $cfg["cgi_base_url"];?>/status.cgi" target="<?php echo $link_target;?>">
				<fieldset>
					<legend>Quick Search:</legend>
					<input type='hidden' name='navbarsearch' value='1'>
					<input type='text' name='host' size='15' class="NavBarSearchItem">
				</fieldset>
			</form>
		</div>
	</div>

	<div class="navsection">
		<div class="navsectiontitle">Reports</div>
			<ul>
				<li><a href="<?php echo $cfg["cgi_base_url"];?>/avail.cgi" target="<?php echo $link_target;?>">Availability</a></li>
				<li>
					<a href="trends.html" target="<?php echo $link_target;?>">Trends</a>
					<a href="<?php echo $cfg["cgi_base_url"];?>/trends.cgi" target="<?php echo $link_target;?>">(Legacy)</a>
				</li>
				<li><a href="<?php echo $cfg["cgi_base_url"];?>/history.cgi?host=all" target="<?php echo $link_target;?>">Alerts</a>
				<ul>
					<li><a href="<?php echo $cfg["cgi_base_url"];?>/history.cgi?host=all" target="<?php echo $link_target;?>">History</a></li>
					<li><a href="<?php echo $cfg["cgi_base_url"];?>/summary.cgi" target="<?php echo $link_target;?>">Summary</a></li>
					<li>
						<a href="histogram.html" target="<?php echo $link_target;?>">Histogram</a>
						<a href="<?php echo $cfg["cgi_base_url"];?>/histogram.cgi" target="<?php echo $link_target;?>">(Legacy)</a>
					</li>
				</ul>
				</li>
				<li><a href="<?php echo $cfg["cgi_base_url"];?>/notifications.cgi?contact=all" target="<?php echo $link_target;?>">Notifications</a></li>
				<li><a href="<?php echo $cfg["cgi_base_url"];?>/showlog.cgi" target="<?php echo $link_target;?>">Event Log</a></li>
			</ul>
	</div>

	<div class="navsection">
		<div class="navsectiontitle">System</div>
			<ul>
				<li><a href="<?php echo $cfg["cgi_base_url"];?>/extinfo.cgi?type=3" target="<?php echo $link_target;?>">Comments</a></li>
				<li><a href="<?php echo $cfg["cgi_base_url"];?>/extinfo.cgi?type=6" target="<?php echo $link_target;?>">Downtime</a></li>
				<li><a href="<?php echo $cfg["cgi_base_url"];?>/extinfo.cgi?type=0" target="<?php echo $link_target;?>">Process Info</a></li>
				<li><a href="<?php echo $cfg["cgi_base_url"];?>/extinfo.cgi?type=4" target="<?php echo $link_target;?>">Performance Info</a></li>
				<li><a href="<?php echo $cfg["cgi_base_url"];?>/extinfo.cgi?type=7" target="<?php echo $link_target;?>">Scheduling Queue</a></li>
				<li><a href="<?php echo $cfg["cgi_base_url"];?>/config.cgi" target="<?php echo $link_target;?>">Configuration</a></li>
			</ul>
	</div>

</div>

<div class=maind>
	<iframe id="main" name="main" src="<?php echo $url; ?>"></iframe>
</div>

</html>
