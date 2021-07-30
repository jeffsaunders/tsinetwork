<?php
/*
Universal website for TSI Network, encompassing all of it's individual properties into a single, consolidated website with multiple point of entry (one per domain)

Each site consists of four main sections, each with its own content (and potential subsections), defined as:
	Header - Top header including logo and main menu.
	Banner - Defines the sites purpose and promotes it's content.
	Body - The meat of the thing.  Contains the main content in an appropriate fashion for the site's purpose.
	Footer - Contact and policy information, with additional global links
The content for each section may be found in "includes" with a descriptive naming convention: sectionIdentity.php (i.e. headerMenus.php)

Copyright 1999-2021, TSI Network, LLC
Authored by Jeff S. Saunders 12/19/2020
Modified by Jeff S. Saunders 07/20/2021
*/

// Define some global system settings
date_default_timezone_set('America/Los_Angeles'); // Pacific Time
error_reporting(E_ALL);  // Show ALL, including warnings and notices (comment after launch)
//error_reporting(E_ERROR);  // Just show hard errors (for after launch)
ini_set('display_errors', '1');  // Show 'em


// Handy debug functions -----
//-----
// Dump ALL existing variables and their values
function dump_vars($mixed=null){
	ob_start();
//	var_dump($mixed);
	print_r($mixed);
	$sVars = ob_get_contents();
	ob_end_clean();
	return $sVars;
}

//-----
// Dump array elements variables and their values
function dump_array($mixed=null){
	ob_start();
	$sElem = "<pre>";
	print_r($mixed);
	$sElem .= htmlentities(ob_get_contents());
	$sElem .= "</pre>";
	ob_end_clean();
	return $sElem;
}

//-----
// Dump PDO result set.  *Note - Result Set can only be iterated once as the pointer moves.
// Set $html=1 if web page
function dump_rs($rs=null,$html=0){
	while($row = $rs->fetch(PDO::FETCH_ASSOC)){
		foreach($row as $cName => $cValue){
			echo "$cName => $cValue".($html ? "&emsp;" : "\t");
		}
		echo ($html ? "<br>" : "\r\n");
	}
}

// Other handy functions -----
//-----
// Is passed value odd?
function is_odd($num){
	return (is_numeric($num)&($num&1));
}

//-----
// Is passed value even?
function is_even($num){
	return (is_numeric($num)&(!($num&1)));
}

//-----
// Covert a passed number into it's text equiv.
function numToText($num = false){
	$num = str_replace(array(',', ' '), '' , trim($num));
	if(! $num) {
		return false;
	}
	//$num = (int) $num;
	$num = floor($num); // Better for LARGE integers
	$words = array();
	$list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',	'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen');
	$list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
	$list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion', 'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion', 'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion');
	$num_length = strlen($num);
	$levels = (int) (($num_length + 2) / 3);
	$max_length = $levels * 3;
	$num = substr('00' . $num, -$max_length);
	$num_levels = str_split($num, 3);
	for ($i = 0; $i < count($num_levels); $i++) {
		$levels--;
		$hundreds = (int) ($num_levels[$i] / 100);
		$hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ' ' : '');
		$tens = (int) ($num_levels[$i] % 100);
		$singles = '';
		if ( $tens < 20 ) {
			$tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
		} else {
			$tens = (int)($tens / 10);
			$tens = ' ' . $list2[$tens] . ' ';
			$singles = (int) ($num_levels[$i] % 10);
			$singles = ' ' . $list1[$singles] . ' ';
		}
		$words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
	} //end for loop
	$commas = count($words);
	if ($commas > 1) {
		$commas = $commas - 1;
	}
//	return implode(' ', $words);
	return trim(preg_replace('~[\\s]+~', ' ', implode(' ', $words))); // Get's rid of crap spaces around words.
}

//-----

// Interrogate the URL to determine which site to present here, then set vars & branch accordingly
$aHost = array_reverse(explode(".",$_SERVER['HTTP_HOST']));
$domain = strtolower($aHost[1]).".".strtolower($aHost[0]);

//print_r($aHost);
// Development - this requires an SSL exception be set in the browser; might eventually get a wildcard cert, but I'm reluctant due to manual quarterly renewals requiring management
$host = "";
if (isset($aHost[2]) && strtolower($aHost[2]) !== "www"){
	$host = strtolower($aHost[2]);
}else{
	$host = strtolower($aHost[1]);
}

//echo $host;
//if (isset($aHost[1]) && strtolower($aHost[1]) == "tsinetwork"){
//	$host =  strtolower($aHost[2]);
//}elseif (isset($aHost[2])){
//	$host =  strtolower($aHost[1]);
//}

// If a host is specified, then set the domain accordingly
switch ($host){
	case "www":
		$domain = "tsinetwork.biz";
	break;

	case "dennistobler":
		$domain = "dennistobler.com";
	break;

	case "footballforecast":
		$domain = "footballforecast.com";
	break;

	case "thoroughbredforecast":
		$domain = "thoroughbredforecast.com";
	break;

	case "thoroughbredracingforecast":
		$domain = "thoroughbredforecast.com";
	break;

	case "gamblingbroadcast":
		$domain = "gamblingbroadcast.com";
	break;

	case "nowplaceyourbets":
		$domain = "nowplaceyourbets.com";
	break;

	default: // None of the above - no host was defined so send 'em to TSI site.
		$domain = "tsinetwork.biz";
	break;

}

// Force domain
//$domain = "dennistobler.com";
// END Development

// Set vars based on the value of $domain
switch ($domain){
	case "tsinetwork.biz":
		$site		= "tsi";
		$siteName	= "TSI Network - A Worldwide Network of Pros Serving You";
		$favicon	= "TSIFavicon.ico";
		$headerLogo	= "TSIHeaderLogo.png";
		$themeColor	= "#DA1B25";
		$themeText	= "#FFFFFF";
		$menuColor	= "#FFFFFF";
		$textColor	= "#626262";
		$linkColor	= "#626262";
		$NPYBColor	= "#FFD86D";
		$NPYBText	= "#000000";
		$GBColor	= "#FF8806";
		$GBText		= "#000000";
		$FFWColor	= "#35B145";
		$FFWText	= "#FFFFFF";
		$TRFColor	= "#405B75";
		$TRFText	= "#FFFFFF";
		$DTColor	= "#84BA3F";
		$DTText		= "#FFFFFF";
	break;

	case "dennistobler.com":
		$site		= "dt";
		$siteName	= "Dennis Tobler's Archives";
		$dbName		= "tsi_dennistobler";
		$favicon	= "DTFavicon.ico";
		$headerLogo	= "DTHeaderLogo.png";
		$themeColor	= "#84BA3F";
		$themeText	= "#000000";
		$menuColor	= "#FFFFFF";
		$textColor	= "#626262";
		$linkColor	= "#626262";
	break;

	case "footballforecast.com":
		$site		= "ffw";
		$siteName	= "Football Forecast Weekly";
		$favicon	= "FFWFavicon.ico";
		$headerLogo	= "FFWHeaderLogo.png";
		$themeColor	= "#35B145";
		$themeText	= "#FFFFFF";
		$menuColor	= "#FFFFFF";
		$textColor	= "#626262";
		$linkColor	= "#626262";
	break;

	case "thoroughbredforecast.com" || "thoroughbredracingforecast.com":
		$site		= "trf";
		$dbName		= "tsi_thoroughbredforecast";
		$siteName	= "Thoroughbred Racing Forecast";
		$favicon	= "TRFFavicon.ico";
		$headerLogo	= "TRFHeaderLogo.png";
		$themeColor	= "#70BD6A";
		$themeText	= "#FFFFFF";
		$menuColor	= "#FFFFFF";
		$textColor	= "#626262";
		$linkColor	= "#000000";
	break;

	case "gamingbroadcast.com" || "gamblingbroadcast.com":
		$site		= "gb";
		$siteName	= "Gambling Broadcast Network";
		$favicon	= "GBFavicon.ico";
		$headerLogo	= "GBHeaderLogo.png";
		$themeColor	= "#FF8806";
		$themeText	= "#000000";
		$menuColor	= "#FFFFFF";
		$textColor	= "#626262";
		$linkColor	= "#626262";
	break;

	case "nowplaceyourbets.com":
		$site		= "npyb";
		$siteName	= "Now Place Your Bets - A Documentary on Sports Betting in America";
		$favicon	= "NPYBFavicon.ico";
		$headerLogo	= "NPYBHeaderLogo.png";
		$themeColor	= "#FFD86D";
		$themeText	= "#000000";
		$menuColor	= "#FFFFFF";
		$textColor	= "#626262";
		$linkColor	= "#626262";
	break;

	default: // None of the above - got her erroneously, so send 'em to TSI site.
        header("Location: https://tsinetwork.biz");
	break;

}

// Database Connection
if (isset($dbName)){ // Only do this if the site has an associated database

	// Pull in the database login credentials from the secure directory
	require("../secure/dbCredentialsTSI.php");

	try{
		$dbLink = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
		$dbLink->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}
	catch(PDOException $error){

		// Function to dump ALL existing variables and their values
		function dump_vars($mixed=null){
			ob_start();
			print_r($mixed);
			$sVars = ob_get_contents();
			ob_end_clean();
			return $sVars;
		}

		// Log any error
		file_put_contents("/var/log/httpd/pdo_log", "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
		die($error->getMessage());
	}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

	<title><?php echo $siteName; ?></title>

	<!-- START META tags -->
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="title" content="<?php echo $siteName;?>">
	<meta http-equiv="reply-to" content="info@tsinetwork.biz">
	<meta name="author" content="Network Resources (nrnet.com)">
	<meta name="resource-type" content="Document">
	<meta name="revisit-after" content="1 Day">
	<meta name="classification" content="Entertainment">
	<meta name="distribution" content="Global">
	<meta name="rating" content="General">
	<meta name="copyright" content="Copyright 1999-<?php echo date("Y");?>, TSI Network, LLC.  All Rights Reserved.">
	<meta name="language" content="en-us">
	<meta name="description" content="TSI Network is dedicated to providing you with the best experience in sports betting & gaming content. From award-winning documentaries to a legacy of sports betting and horse racing oriented productions, TSI Network is your premier source for news, entertainment, and information on sports betting, horse racing, and the overall gaming lifestyle, both in Las Vegas and around the world.">
	<meta name="keywords" content="gaming, gambling, football, horse racing, thoroughbred racing, sports betting, picks, winners, casino, sports book, television, movie, film, documentary, radio, las vegas, vegas, lifestyle">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="robots" content="all"> <!-- all, none, index, noindex, follow or nofollow-->
	<!--END META tags-->

	<!-- Favicon -->
	<link rel="shortcut icon" href="images/<?php echo $favicon; ?>" />

	<!-- START Stylesheets (CSS) -->
	<!-- font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,500,500i,600,700,800,900|Poppins:200,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Dosis:300,400,500,600,700,800">

	<!-- Plugins -->
	<link rel="stylesheet" type="text/css" href="css/plugins-css.css" />

	<!-- revoluation -->
	<link rel="stylesheet" type="text/css" href="revolution/css/settings.css" media="screen" />

	<!-- Typography -->
	<link rel="stylesheet" type="text/css" href="css/typography.css" />

	<!-- Shortcodes -->
	<link rel="stylesheet" type="text/css" href="css/shortcodes/shortcodes.css" />

	<!-- Style -->
	<link rel="stylesheet" type="text/css" href="css/style.css" />

	<!-- Responsive -->
	<link rel="stylesheet" type="text/css" href="css/responsive.css" />

	<!-- Default Style Override -->
	<style>
		/* Global */
		img { max-width: 100%; height: auto; } /* Scale all images for screen width */
		pre {
			white-space: pre-wrap;       /* Since CSS 2.1 */
			white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
			white-space: -pre-wrap;      /* Opera 4-6 */
			white-space: -o-pre-wrap;    /* Opera 7 */
			word-wrap: break-word;       /* Internet Explorer 5.5+ */
		}
		/* Theme */
		.theme-color { color: <?php echo $themeColor; ?>; }
		p { font-weight: normal; line-height: 1.5; color: <?php echo $textColor; ?>; }
		p.black { font-weight: normal; line-height: 1.5; color: #000000; }
		p.white { font-weight: normal; line-height: 1.5; color: #FFFFFF; }

		/* Header/Menu */
		.header.text-dark .topbar ul li a:hover { color: <?php echo $menuColor; ?>; }
		.header.text-dark .mega-menu .menu-links > li > a:hover { color: <?php echo $menuColor; ?>; }
		.header.text-dark .mega-menu .menu-links > li.active > a { color: <?php echo $menuColor; ?>; }
		.header.text-dark .search-cart i.icon:hover { color: <?php echo $menuColor; ?>; }
		.header.text-dark .search .search-btn:hover { color: <?php echo $menuColor; ?>; }
		.header.text-dark .mega-menu .drop-down a:hover i.fa, .header.text-dark .mega-menu .drop-down-tab-bar a:hover i.fa  { color: <?php echo $menuColor; ?>; }
		.header.text-dark .mega-menu .menu-links > li.active .drop-down-multilevel a:hover, .header.text-dark .mega-menu .drop-down-multilevel a:hover { color: <?php echo $menuColor; ?>; }
		.header.text-dark .mega-menu .menu-links > li.active .drop-down-multilevel li.active .drop-down-multilevel li a:hover { color: <?php echo $menuColor; ?>; }
		.header.text-dark .mega-menu .menu-links > li.active .drop-down-multilevel li.active .drop-down-multilevel li.active a { color: <?php echo $menuColor; ?>; background: #282828; }
		.header.text-dark .mega-menu .menu-links > li.active .drop-down-multilevel li.active .drop-down-multilevel li a .drop-down-multilevel li a:hover { color: <?php echo $menuColor; ?>; }
		.header.text-dark .mega-menu .menu-links > li.active .drop-down-multilevel li.active .drop-down-multilevel li.active .drop-down-multilevel li.active a { color: <?php echo $menuColor; ?>; background: #282828; }
		.header.text-dark .mega-menu .menu-links > li.active .drop-down-multilevel li.active i { color: <?php echo $menuColor; ?>; }
		.header.text-dark .mega-menu .menu-links > li.active .drop-down-multilevel li.active .drop-down-multilevel li.active i { color: <?php echo $menuColor; ?>; }
		.header.text-dark .mega-menu .menu-links > li.active .drop-down-multilevel li.active:hover .drop-down-multilevel li a:hover i { color: <?php echo $menuColor; ?>; }
		.header.text-dark .mega-menu .menu-links > li.active .drop-down-multilevel li.active .drop-down-multilevel li.active .drop-down-multilevel li a:hover { color: <?php echo $menuColor; ?>; }
		.header.text-dark .mega-menu .menu-links > li.active .drop-down-multilevel li.active .drop-down-multilevel li.active a { color: <?php echo $menuColor; ?>; background: #282828; }
		.header.text-dark .mega-menu .drop-down-multilevel a:hover i.fa-indicator { color: <?php echo $menuColor; ?>; }
		.header.text-dark .mega-menu .drop-down-multilevel li.active a i.fa-indicator { color: <?php echo $menuColor; ?>; }
		.header.text-dark .mega-menu .menu-links > li.active .drop-down li.active a { color: <?php echo $themeColor; ?>; }
		.header.text-dark .mega-menu .menu-links > li.active .drop-down li a:hover { color: <?php echo $themeColor; ?>; }
		.header.text-dark .mega-menu .drop-down a:hover, .header.text-dark .mega-menu .drop-down-tab-bar a:hover { color: <?php echo $themeColor; ?>; }
		/* Fixed menu when scrolling */
/*		.header.text-dark .mega-menu.desktopTopFixed .menu-list-items { background: rgba(0, 0, 0, 0.2); } */
		.header.text-dark .mega-menu.desktopTopFixed .menu-list-items { background: #A2A2A2; } /* Solid, fixed color instead */

		/* Links */
		a, button, input a:visited { outline: medium none !important; color: <?php echo $linkColor; ?>; }
		a:focus, a:hover { color: <?php echo $themeColor; ?>; text-decoration: none !important; }
		.isotope-filters  button.active, .isotope-filters  button:hover { background: <?php echo $themeColor; ?>; color: <?php echo $menuColor; ?>; border-color: <?php echo $themeColor; ?>; }

		/* Elements */
		#back-to-top .top { z-index: 999; position: fixed; margin: 0px; color: <?php echo $themeText; ?>; transition:all .5s ease-in-out; position:fixed; bottom:105px; right:15px; border-radius: 3px; z-index: 999; background: transparent; font-size: 14px; background: <?php echo $themeColor; ?>; width: 40px; height: 40px; text-align: center; }
		img#map-footer { height: auto; width: 290px; }
		@media (min-width: 375px) {img#map-footer { height: auto; width: 355px; } }
		@media (min-width: 400px) {img#map-footer { height: auto; width: 420px; } }
/*@media (min-width: 1300px) {
.container { max-width: 1200px; }
}*/
		/* Footer */
		.footer-useful-link ul li a:hover { color: <?php echo $menuColor; ?>; }
		.footer-widget a { color: rgba(255,255,255,0.5); } /* Need half opacity (alpha) so use RGBA */
		.footer-widget a:hover { color: <?php echo $menuColor; ?>; }
		.footer .footer-social ul li a:hover { color: <?php echo $menuColor; ?> !important; }
	</style>
	<!-- END Stylesheets (CSS) -->

</head>

<body>

<!-- Preloader -->
<div id="pre-loader">
    <img src="images/loader.svg" alt="">
</div>

<!-- "Top" anchor -->
<a name="top"></a>

<!-- Header -->
<header id="header" class="header transparent fullWidth text-dark">
	<div class="menu">
		<!-- menu start -->
		<nav id="menu" class="mega-menu">
			<!-- menu list items container -->
			<section class="menu-list-items">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12 col-md-12">
							<!-- menu logo -->
							<ul class="menu-logo">
								<li>
									<a href="/"><img id="logo_img" src="images/<?php echo $headerLogo; ?>" alt="logo"> </a>
								</li>
							</ul>
							<!-- menu links -->
							<div class="menu-bar">
								<ul class="menu-links">
<?php
	// Pull in the menus
	require("includes/headerMenus.php");
?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</section>
		</nav> <!-- menu end -->
	</div>
</header>

<!-- Banner -->
<?php
// Define Banner Content
switch ($site){

	case "tsi":
		// Pull in the banner for TSI Network
		require("includes/bannerTSI.php");
		break;

	case "dt":
		// Pull in the banner for DennisTobler
		require("includes/bannerDT.php");
		break;

	case "ffw":
		break;

	case "trf":
		// Pull in the banner for Thoroughbred Forecast
		require("includes/bannerTRF.php");
		break;

	case "gb":
		break;

	case "npyb":
		break;

	default:
		// Pull in the banner for TSI Network by default
		require("includes/bannerTSI.php");
		break;

}
?>

<!-- Body -->
<?php
// Define Body Content
switch ($site){

	case "tsi":
		// Pull in the body for TSI Network
		require("includes/bodyTSI.php");
		break;

	case "dt":
		// Pull in the body for Dennis Tobler
		require("includes/bodyDT.php");
		break;

	case "ffw":
		break;

	case "trf":
		// Pull in the bbody for Thoroughbred Forecast
		require("includes/bodyTRF.php");
		break;

	case "gb":
		break;

	case "npyb":
		break;

	default:
		// Pull in the body for TSI Network by default
		require("includes/bodyTSI.php");
		break;

}
?>

<!-- "Contact" anchor -->
<a name="contact"></a>

<!-- Contact Form (modal) -->
<div class="modal fade bd-example-modal-md" id="contactForm" tabindex="-1" role="dialog" aria-labelledby="contactLabel" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-title" id="contactLabel">
					<div class="section-title mb-10">
						<h6>How Can We Help?</h6>
						<h2>Contact Us</h2>
                    </div>
                  </div>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
			</div>
			<div class="modal-body">
				<p class="mb-50">Got a question?  We'd love to hear from you.  Send us a message and we'll get back to you as soon as possible</p>
				<div id="formmessage">Success/Error Message Placeholder</div>
				<form id="contactform" role="form" method="post" action="process/contact-form.php">
					<div class="contact-form clearfix">
						<!-- Hidden Fields -->
						<input type="hidden" name="website" value="<?php echo $domain; ?>">
						<input type="hidden" name="action" value="sendEmail">

						<!-- Visible Fields -->
						<div class="section-field full-width">
							<input id="name" type="text" placeholder="Name*" class="form-control" name="name">
						</div>
						<div class="section-field full-width">
							<input type="email" placeholder="Email*" class="form-control" name="email">
						</div>
						<div class="section-field full-width">
							<input type="text" placeholder="Phone" class="form-control" name="phone">
						</div>
						<div class="section-field full-width" style="text-align: center">
							Contact Method Preference:&nbsp;&nbsp;
							<input type="radio" name="preference" id="email" value="email"> Email&nbsp;&nbsp;
							<input type="radio" name="preference" id="phone" value="phone"> Phone&nbsp;&nbsp;
							<input type="radio" name="preference" id="either" value="either"> Either<br><br>
						</div>
						<div class="section-field full-width textarea">
							<textarea class="input-message form-control" placeholder="Message*" rows="5" name="message"></textarea>
						</div>
						<!-- Google reCaptcha-->
						<div class="g-recaptcha section-field clearfix" data-sitekey="6LcA4fIaAAAAAFCu3FmNi30VgGT88TSpQ4U6_bCM"></div>
						<!-- Submit -->
						<div class="section-field full-width submit-button" style="text-align: center">
							<button id="submit" name="submit" type="submit" value="Send" class="btn btn-secondary"><span> Send message </span> <i class="fa fa-paper-plane"></i></button>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
<!--				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
			</div>
		</div>
	</div>
</div>
<!-- Put physical contact info at the bottom of the modal -->

<!-- Footer -->
<footer class="footer page-section-pt black-bg">
	<div class="container">
		<div class="row">
			<!-- Left Column -->
			<div class="col-lg-5 col-md-6 col-sm-6 sm-mb-30">
				<img id="map-footer" src="images/WorldMap2.png" alt="">
			</div>
			<!-- Center Column -->
			<div class="col-lg-4 col-md-6 col-sm-6">
				<!-- Contact -->
				<div class="footer-useful-link footer-hedding"> <!-- Great spelling! (foreign designed template) -->
					<h6 class="text-white mb-20 mt-10 text-uppercase">CONTACT US</h6>
					<ul class="addresss-info">
						<li><i class="fa fa-map-marker"></i> <p>TSI Network, LLC<br>7500 West Lake Mead Blvd #9&minus;303<br>Las Vegas, Nevada&nbsp;&nbsp;89128</p></li>
						<li><i class="fa fa-phone"></i> <a href="tel:7027176997"> <span>(702) 717-6997 </span> </a> </li>
<!--						<li><i class="fa fa-envelope-o"></i> <a href="#">Contact Form </a></li> <!-- Link here to contact form modal -->
						<li><i class="fa fa-envelope-o"></i> <a class="popup-modal" data-toggle="modal" href="#contactForm" title="Contact Us">Contact Form </a></li>
					</ul>
					<br>
					<!-- Social Media -->
					<div class="social-icons color-hover">
						<h6 class="text-white mb-20 mt-10 text-uppercase">FOLLOW US</h6>
						<ul>
							<li class="social-facebook"><a href="https://facebook.com/dennistobler" target="_blank"><i class="fa fa-facebook"></i></a></li>
							<li class="social-twitter"><a href="https://twitter.com/football4cast" target="_blank"><i class="fa fa-twitter"></i></a></li>
							<li class="social-youtube"><a href="https://youtube.com/user/DennisTobler" target="_blank"><i class="fa fa-youtube"></i> </a></li>
							<li class="social-vimeo"><a href="#"><i class="fa fa-vimeo"></i> </a></li>
							<li class="social-linkedin"><a href="https://linkedin.com/in/dennis-tobler-005114161" target="_blank"><i class="fa fa-linkedin"></i> </a></li>
							<li class="social-amazon"><a href="https://amazon.com/gp/video/detail/B082GZ9YWY/ref=atv_dp_share_cu_r" target="_blank"><i class="fa fa-amazon"></i> </a></li>
	  					</ul>
					</div>
				</div>
			</div>
			<!-- Right Column -->
			<div class="col-lg-3 col-md-6 col-sm-6 sm-mb-30">
				<div class="footer-useful-link footer-hedding"> <!-- Great spelling! (foreign designed template) -->
					<h6 class="text-white mb-20 mt-10 text-uppercase">VISIT US</h6>
					<ul>
						<li><a href="https://tsinetwork.biz">TSI Network</a></li>
						<li><a href="https://footballforecast.com">Football Forecast</a></li>
						<li><a href="https://thoroughbredforecast.com">Thoroughbred Forecast</a></li>
						<li><a href="https://gamblingbroadcast.com">Gambling Broadcast</a></li>
						<li><a href="https://nowplaceyourbets.com">Now Place Your Bets</a></li>
						<li><a href="https://dennistobler.com">Dennis Tobler's Archives</a></li>
					</ul>
				</div>
			</div>
		</div>
		<!-- Bottom Bar -->
		<div class="footer-widget mt-40 ">
			<div class="row">
				<!-- Left Column -->
				<div class="col-lg-6 col-md-6">
					<div class="footer-social mt-10">
						<p> &copy;Copyright <span id="copyright"> 1999-<script>document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))</script></span> <a href="https://tsinetwork.biz"> TSI Network, LLC </a> All Rights Reserved </p>
					</div>
				</div>
				<!-- Right Column -->
				<div class="col-lg-6 col-md-6">
					<div class="footer-social mt-10">
						<ul class="text-left text-md-right">
							<li class="list-inline-item"><a href="#">Terms &amp; Conditions </a> &nbsp;&nbsp;&nbsp;|</li><!-- Link here to T&C modal -->
							<li class="list-inline-item"><a href="#">Privacy Policy </a> </li><!-- Link here to Privacy Policy modal -->
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>

<!-- Floating Go To Top Link -->
<div id="back-to-top"><a class="top arrow" href="#top"><i class="fa fa-angle-up"></i> <span>TOP</span></a></div>

<!-- Load Javascript -->
<!-- jquery -->
<script src="js/jquery-3.4.1.min.js"></script>

<!-- plugins-jquery -->
<script src="js/plugins-jquery.js"></script>

<!-- plugin_path -->
<script>var plugin_path = 'js/';</script>

<!-- REVOLUTION JS FILES -->
<script src="revolution/js/jquery.themepunch.tools.min.js"></script>
<script src="revolution/js/jquery.themepunch.revolution.min.js"></script>

<!-- SLIDER REVOLUTION 5.0 EXTENSIONS  (Load Extensions only on Local File Systems !  The following part can be removed on Server for On Demand Loading) -->
<script src="revolution/js/extensions/revolution.extension.actions.min.js"></script>
<script src="revolution/js/extensions/revolution.extension.carousel.min.js"></script>
<script src="revolution/js/extensions/revolution.extension.kenburn.min.js"></script>
<script src="revolution/js/extensions/revolution.extension.layeranimation.min.js"></script>
<script src="revolution/js/extensions/revolution.extension.migration.min.js"></script>
<script src="revolution/js/extensions/revolution.extension.navigation.min.js"></script>
<script src="revolution/js/extensions/revolution.extension.parallax.min.js"></script>
<script src="revolution/js/extensions/revolution.extension.slideanims.min.js"></script>
<script src="revolution/js/extensions/revolution.extension.video.min.js"></script>
<!-- revolution custom -->
<script src="revolution/js/revolution-custom.js"></script>

<!-- Google reCaptcha -->
<script src='https://www.google.com/recaptcha/api.js'></script>

<!-- custom -->
<script src="js/custom.js"></script>

</body>
</html>


<!-- !!!!!!!!!!!!!!!!!!!Add Google Analytics!!!!!!!!!!!!!!!!!!!!!!!!!!!!!important-->