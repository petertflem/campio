<? 
session_start(); 
header("Cache-control: private");  // IE 6 Fix.
ob_start(); 

include 'lib/safeInclude.php';


// finn ut hvilken side som skal vises
		$p = $_GET['p'];
		$page = 'frontpage.php'; //default side
		if (isset($p)) {
			$page = $p.'.php';
			if (!is_file($page)) { 
				$page = $p.'/'.$page; 
			}
		}
		$page_exploded = explode(".", $page);
		$page_exploded = explode("/", $page_exploded[0]);
		$pagegroup = $page_exploded[0];
		$subpage = $page_exploded[1];



$now = time();
$last_chance_date = mktime(8, 0, 0, 6, 10, 2008);
$too_late_date = mktime(8, 0, 0, 6, 10, 2008);

$registration_possible = $now < $last_chance_date;
$registration_last_change = !$registration_possible && $now < $too_late_date;
$registration_too_late = $now >= $too_late_date;


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Nattscenario</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="title" content="Nattscenario">
<META NAME="Description" CONTENT="Nattscenario">
<META NAME="Keywords" CONTENT="tencamp nattscenario oks">
<link rel="image_src" href="http://www.nattscenario.no/images/2011/page_thumb.jpg" />
<link href="css/style2011.css" rel="stylesheet" type="text/css">
<!--[if IE 6]>
<link href="css/ie6.css" rel="stylesheet" type="text/css">
<![endif]-->
</head>

<body>
<div class="pagegroup_<?=$pagegroup?>">
<div class="subpage_<?=$subpage?>">

<style type="text/css">
#contentarea {background:url('<?=$page == 'frontpage.php' ? 'images/2011/background_frontpage.jpg' :'images/2011/background_info.jpg' ?>');}
<?if ($page == 'frontpage.php') {?>
#frontpagelink {display:none;}
<? } ?>

#shadow {
    background-image: url('images/2011/background_shadow.png');
}
#corners {
    background-image: url('images/2011/background_corners.png');
}
</style>

<div align="center" >
	<div id="contentarea">
	    <div id="corners"></div>
	
		<a href="?"><div id="frontpagelink"></div></a>

		<div id="menubar">
			<a id="menu_info" href="?p=info"<?=$pagegroup == 'info' ? ' class="selected"' : ''?>></a>
			<a id="menu_oppdrag" href="?p=oppdrag"<?=$pagegroup == 'oppdrag' ? ' class="selected"' : ''?>></a>
			<a id="menu_media" href="?p=media"<?=$pagegroup == 'media' ? ' class="selected"' : ''?>></a>
		    <a id="menu_paamelding" href="?p=paamelding"<?=$pagegroup == 'paamelding' ? ' class="selected"' : ''?>></a>
		</div>
	

			<div id="pagearea">
				<? if (checkPageLegal($page)) include $page; ?>
			</div>

		<div id="facebooklink">
			<a href="http://www.facebook.com/share.php?u=http://www.nattscenario.no" onclick="return fbs_click();" target="_blank" class="fb_share_link">Del på Facebook</a>
		</div>
	</div>
	<div id="shadow"></div>
</div>

</div>
</div>
<!-- google analytics -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-16589374-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<!-- google analytics end-->

<script src="js/main.js" type="text/javascript"></script>

</body>
</html>
<?
    include_once 'lib/replacePngTags.php';
    echo replacePngTags(ob_get_clean());
?>