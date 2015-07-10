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
$too_late_date = mktime(0, 0, 0, 7, 1, 2008);

$registration_possible = $now < $too_late_date;
$registration_too_late = $now >= $too_late_date;
$registration_too_late_text = "Det vil fortsatt være mulighet å melde seg på Nattscenario. Det skjer i Infoteltet i uteamfiet som er åpent ettermiddager og etter kveldsmøtet under CampMeeting. Infoteltet har også egen mobil: 934 24 270";


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>NattScenario</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META NAME="Description" CONTENT="NattScenario">
<META NAME="Keywords" CONTENT="tencamp nattscenario oks">
<meta name="robots" content="noindex">
<link href="css/style2008.css" rel="stylesheet" type="text/css">
<script src="js/mootools/mootools.v1.1.js" type="text/javascript"></script>
</head>

<body>
<div class="pagegroup_<?=$pagegroup?>">
<div class="subpage_<?=$subpage?>">

<style type="text/css">
#contentarea {background:url('<?=$page == 'frontpage.php' ? 'images/2008/bg_forside.jpg' :'images/2008/bg_info.jpg' ?>');}
<? if($page == 'frontpage.php') { ?>
#frontpagelink {display:none;}
<? } ?>
</style>

<div align="center" >
	<div id="contentarea" >
		<a href="?"><div id="frontpagelink"></div></a>

		<div id="menu" align="right">
			<div style="width:109px"><a href="?p=info"><img src="images/2008/menu_info.png" width="109" height="29"></a></div>
			<div style="width:142px"><a href="?p=media"><img src="images/2008/menu_media.png" width="142" height="29"></a></div>
			<div><a href="<?=$registration_too_late ? '' : 'http://www.oks.no/index.asp?did=562928&argument=påmelding&page_number=1&&title=P%E5melding+TenCamp+Nattscenario+og+overnatting'?>" onClick="onClickRegistration()"><img src="images/2008/menu_paamelding.png" width="182" height="29"></a></div>
		</div>


			<div id="pagearea">
				<? if (checkPageLegal($page)) include $page; ?>		
			</div>		

	</div>
</div>

</div>
</div>
<!-- google analytics -->
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
<script type="text/javascript">
	_uacct = "UA-1990216-1";
	urchinTracker();
</script>
<!-- google analytics end-->
<script type="text/javascript">
function onClickRegistration() {
	<? if($registration_too_late) { ?>
	alert('<?=$registration_too_late_text?>');
	<? } ?>
}
</script>

</body>
</html>
<?
    include_once 'lib/replacePngTags.php';
    echo replacePngTags(ob_get_clean());
?>
