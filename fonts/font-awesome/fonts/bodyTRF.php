<!-- BEGIN include bodyTRF -->

<?php
/*
Body section content for the Thoroughbred Forecast site
Copyright 1999-2021, TSI Network, LLC
Authored by Jeff S. Saunders 12/19/2020
Modified by Jeff S. Saunders 07/20/2021
*/
?>

<?php
// NPYB Promo
require("includes/promoNPYB.php");
?>

<!-- Body -->

<!-- Handicappers anchor -->
<a name="handicappers"></a>

<?php
// Get the handicappers
$query = "
	SELECT *
	FROM handicappers
	WHERE active = :active
	ORDER BY priority ASC
";
try{
	$rsHandicappers = $dbLink->prepare($query);
	// Use a custom values array name so it can be called later without possible corruption by a subsequent query
	$aHValues = array(
		':active'		=> "1"
	);
	//$preparedQuery = str_replace(array_keys($aHValues), array_values($aHValues), $query); //Debug
	//echo $preparedQuery;die();
	$rsHandicappers->execute($aHValues);
}
catch(PDOException $error){
	// Log any error
	file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
}

$handicappersCnt = 0;
while($handicapper = $rsHandicappers->fetch(PDO::FETCH_ASSOC)){
	$handicappersCnt++;
	$fullname = $handicapper['first_name']." ".$handicapper['last_name'];
?>

<!-- <?php echo $handicapper['moniker']; ?> Promo (modal) -->
<div class="modal fade bd-example-modal-lg" id="handicapper<?php echo $handicapper['moniker']; ?>" tabindex="-1" role="dialog" aria-labelledby="handicapper<?php echo $handicapper['moniker']; ?>Label" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-title" id="handicapper<?php echo $handicapper['moniker']; ?>Label">
					<div class="section-title mb-10">
						<h6>HANDICAPPER</h6>
						<h2><?php echo $fullname; ?></h2>
                    </div>
                  </div>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
			</div>
			<div class="modal-body">
				<span class="dropcap square large" style="background-color: #FFFFFF; padding: 5px 5px 60px 0px;"><img src="images/<?php echo $handicapper['thumbnail']; ?>" alt="<?php echo $fullname; ?>"></span><?php echo $handicapper['bio']; ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<?php
// Get the tracks this handicapper handicaps
$query = "
	SELECT *
	FROM tracks
	WHERE uid IN (".$handicapper['tracks'].")
	AND active = :active
	ORDER BY priority ASC
";
try{
	$rsTracks = $dbLink->prepare($query);
	$aTValues = array(
		':active'	=> "1"
	);
	//$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
	//echo $preparedQuery;//die();      		':tracks'	=> $handicapper['tracks']
	$rsTracks->execute($aTValues);
}
catch(PDOException $error){
	// Log any error
//	file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
	echo $error;
}


//dump_rs($rsTracks, 1);
//while($track = $rsTracks->fetch(PDO::FETCH_ASSOC)){
//
//echo $track['track']."<br>";
//	$fullname = $handicapper['first_name']." ".$handicapper['last_name'];
//}
?>


<!-- <?php echo $handicapper['moniker']; ?> Picks (modal) -->
<div class="modal fade bd-example-modal-lg" id="picks<?php echo $handicapper['moniker']; ?>" tabindex="-1" role="dialog" aria-labelledby="picks<?php echo $handicapper['moniker']; ?>Label" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-title" id="picks<?php echo $handicapper['moniker']; ?>Label">
					<div class="section-title mb-10">
						<h6>PICKS</h6>
						<h2><?php echo $fullname; ?></h2>
                    </div>
                  </div>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
			</div>
 			<div class="modal-body">
				<span class="dropcap square large" style="background-color: #FFFFFF; padding: 5px 5px 60px 0px;"><img src="images/<?php echo $handicapper['thumbnail']; ?>" alt="<?php echo $fullname; ?>"></span><h4 style="padding: 0px 0px 5px 4000px;"><?php echo $handicapper['picks_slug']; ?></h4>

<?php
if ($handicapper['tracks'] == 0){
?>

				<h3 align='center'>&mdash;&nbsp;Hold Your Horses&nbsp;&mdash;</h3>
				<h6 align='center'>This handicapper's not quite on board yet...please try again later</h6>
				<h1>O<br>O</h1>
				<br>
 			</div>

<?php
}else{
?>
			</div>
	 			<div class="modal-body">
					<h3 align='center'>&mdash;&nbsp;Coming Soon&nbsp;&mdash;</h3>
					<h6 align='center'>Email <?php echo $handicapper['first_name']; ?>: <a href="mailto:<?php echo $handicapper['email']; ?>"><?php echo $handicapper['email']; ?></a></h6>
					<h6 align='center'><em>Ignore the man behind the curtain below</em><br><i class="fa fa-caret-down"></i>&nbsp;<i class="fa fa-caret-down"></i>&nbsp;<i class="fa fa-caret-down"></i>&nbsp;<i class="fa fa-caret-down"></i>&nbsp;<i class="fa fa-caret-down"></i></h6>
	 			</div>


  <div class="container">
     <div class="row justify-content-center">
       <div class="col-lg-12 col-md-16">
        <div class="tab tab-border">
         <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active show" id="all-tracks-tab" data-toggle="tab" href="#all-tracks" role="tab" aria-controls="signup" aria-selected="false"><i></i> All Tracks </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="one-track-tab" data-toggle="tab" href="#one-track" role="tab" aria-controls="login" aria-selected="true"> <i></i> One Track </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="free-pick-tab" data-toggle="tab" href="#" role="tab" aria-controls="signup" aria-selected="false"><i></i> Free Pick </a>
            </li>
          </ul>
            <div class="tab-pane fade active show" id="all-tracks" role="tabpanel" aria-labelledby="all-tracks-tab">
              <div class="login-box-02 white-bg">
<!--              <div class="pb-50 clearfix">-->
<!--			  <div class="col-lg-12 col-md-16">-->
                  <div class="row align-items-center">

<?php
$tCnt = 0;  // Counter for number of tracks
$aTracks = array();  // Array to contain track UIDs for later queries
while($track = $rsTracks->fetch(PDO::FETCH_ASSOC)){
	$tCnt++;
	$aTracks[] = $track['uid'];
	echo'
					<div class="col">
						<img src="images/'.$track['logo'].'" alt="'.$track['track'].'">
                    </div>
	';
}

// Put the tracks in ASC order and create a CSV string
asort($aTracks);
$tracks = implode(",", $aTracks);

// Get the subscription types for this bundle
$query = "
	SELECT *
	FROM subscription_types
	WHERE tracks = :tracks
	AND active = :active
	AND handicapper = :handicapper
	ORDER BY priority ASC
";
try{
	$rsSubTypes = $dbLink->prepare($query);
	$aSTValues = array(
		':tracks'		=> $tracks,
		':active'		=> "1",
		':handicapper'	=> $handicapper['uid']
	);
	//$preparedQuery = str_replace(array_keys($aSTValues), array_values($aSTValues), $query); //Debug
	//echo $preparedQuery;//die();      		':tracks'	=> $handicapper['tracks']
	$rsSubTypes->execute($aSTValues);
}
catch(PDOException $error){
	// Log any error
//	file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
	echo $error;
}

?>
                  </div>
<!--				</div>-->

			  <div class="container">
                  <div class="row justify-content-center">
					<div class="col mt-4 text-center">
						<h3>All <?php echo ucwords(numToText($tCnt)); ?> Tracks for One Bundle Price</h3>
						<h4>Every Race, Every Track, Every Day</h4>
                    </div>
                  </div>
			  </div>

			  <div class="container">
                  <div class="row justify-content-around mt-4">

<?php
while($sub = $rsSubTypes->fetch(PDO::FETCH_ASSOC)){
	echo'
					<div class="col text-center">
						<button type="button" class="btn btn-secondary btn-lg btn-block" value="'.$sub['uid'].'">'.$sub['period'].'<br>$'.$sub['rate'].'</button>
						<p class="fs-6" style="color: #FF0000; font-weight: bold;">'.$sub['savings'].'</p>
                    </div>
	';
}
?>
                  </div>
			  </div>

			  <div class="container">
                  <div class="row justify-content-center">
					<div class="col mt-4 text-center">
						<p style="font-size: x-small;"><em>*All subscriptions will auto-renew. Cancel any time and your service will stop at the end of your current billing period.</em></p>
                    </div>
                  </div>
			  </div>

<!--                 <h4 class="mb-30">Signup</h4>
                  <div class="row">
                   <div class="section-field mb-20 col-sm-6">
                     <label class="mb-10" for="name">First name* </label>
                       <input id="name" class="web form-control" type="text" placeholder="First name" name="web">
                    </div>
                     <div class="section-field mb-20 col-sm-6">
                     <label class="mb-10" for="name">Last name* </label>
                       <input id="name" class="web form-control" type="text" placeholder="Last name" name="web">
                    </div>
                  </div>
                  <div class="section-field mb-20">
                       <label class="mb-10" for="name">Email* </label>
                        <input type="email" placeholder="Email*" class="form-control" name="email">
                   </div>
                  <div class="section-field mb-20">
                   <label class="mb-10" for="Password">Password* </label>
                     <input id="Password" class="Password form-control" type="password" placeholder="Password" name="Password">
                  </div>
                    <a href="#" class="button btn-block">
                      <span>Signup</span>
                      <i class="fa fa-check"></i>
                   </a>
                </div>
                <div class="login-social text-center clearfix">
                  <ul>
                      <li><a class="fb" href="#"> Facebook</a></li>
                      <li><a class="twitter" href="#"> Twitter</a></li>
                      <li><a class="pinterest" href="#"> google+</a></li>
                  </ul>
                </div>
<!--               </div>-->
<!--            </div>-->


<!--
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade active show" id="one-track" role="tabpanel" aria-labelledby="one-track-tab">
              <div class="login-box-02 white-bg">
                <div class="pb-50 clearfix">
                   <h4 class="mb-30"> Login </h4>
                 <div class="section-field mb-20">
                     <label class="mb-10" for="name">User name* </label>
                       <input id="name" class="web form-control" type="text" placeholder="User name" name="web">
                    </div>
                    <div class="section-field mb-20">
                     <label class="mb-10" for="Password">Password* </label>
                       <input id="Password" class="Password form-control" type="password" placeholder="Password" name="Password">
                    </div>
                    <div class="section-field">
                       <div class="custom-control custom-checkbox mb-30">
                        <input type="checkbox" class="custom-control-input" id="customControlAutosizing">
                        <label class="custom-control-label" for="customControlAutosizing">Remember me</label>
                      </div>
                      </div>
                      <a href="#" class="button btn-block">
                        <span>Log in</span>
                        <i class="fa fa-check"></i>
                     </a>
                  </div>
                  <div class="login-social text-center clearfix">
                    <ul>
                        <li><a class="fb" href="#"> Facebook</a></li>
                        <li><a class="twitter" href="#"> Twitter</a></li>
                        <li><a class="pinterest" href="#"> google+</a></li>
                    </ul>
                  </div>
                 </div>-->
            </div>
          </div>
        </div>
       </div>
     </div>
   </div>













			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
<?php
}
?>
		</div>
	</div>
</div>
<?php

/*  Replaced with dynamic code above...
<!-- Tobler Promo (modal) -->
<div class="modal fade bd-example-modal-lg" id="handicapperTobler" tabindex="-1" role="dialog" aria-labelledby="handicapperToblerLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-title" id="handicapperToblerLabel">
					<div class="section-title mb-10">
						<h6>HANDICAPPER</h6>
						<h2>Dennis Tobler</h2>
                    </div>
                  </div>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
			</div>
			<div class="modal-body">
				<span class="dropcap square large" style="background-color: #FFFFFF; padding: 5px 5px 60px 0px;"><img src="images/TRFDennisHeadshot2.jpg" alt="Dennis Tobler"></span>Dennis Tobler is a fixture in the sports gaming and horse race handicapping business in Las Vegas. A life-long sports participant and sports & horse race bettor, the Nebraska-born and football-bred Tobler has been one of the country's top handicappers. He is one of a handful of experts who help set the betting line in Las Vegas and offshore. Having begun his career in 1972, his sources run very deep in this close knit business.<br><br style="line-height: 1.5;">
				<p>In order to be an expert in the sports/horse racing world, you must have a deep knowledge of the odds and the sources behind the numbers. As simple of a statement as that is...there are very, very few people in the betting world that have any idea of the complexities, resources, and information that goes into making of THE LINE. You must know that to succeed. Yet, most of the public touts, radio handicappers and scammers throw these numbers around as if a 30-year database of old statistics and their power ratings can make them win. That is simply not the case.</p>
				<p>My position as an odds expert and as a consultant to sports books, racing stables, hedge funds and wise guys gives me the advantage over the average bettor and allows me to take the positive-expectation position and have the highest likelihood of winning. In fact, along with the very best software and resources available only to a few people, and experience second to none, I have been able to win and win BIG over the years! Now, my experience and inside sources allow me consult for two of the largest hedge funds operating legally in Nevada. In fact, maybe the only legal hedge funds in Nevada. If there is value anywhere in the Las Vegas market, we grab it, long before the average public bettors even knew there was a positive-edge.</p>
				<p>You are invited to join in the WINNING! Check out our winning programs and hit the jackpot this season!  Personalized service for serious players is available.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- Culler Promo (modal) -->
<div class="modal fade bd-example-modal-lg" id="handicapperCuller" tabindex="-1" role="dialog" aria-labelledby="handicapperCullerLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-title" id="handicapperCullerLabel">
					<div class="section-title mb-10">
						<h6>HANDICAPPER</h6>
						<h2>Jeff Culler</h2>
                    </div>
                  </div>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
			</div>
			<div class="modal-body">
				<span class="dropcap square large" style="background-color: #FFFFFF; padding: 5px 5px 60px 0px;"><img src="images/JeffCullerOnHorse.jpg" alt="Jeff Culler"></span>A meandering road to racing took TSI Chief Handicapper Jeff Culler from army parachutes to a football powerhouse to state capitols and finally to his true passion: Thoroughbred racing.<br><br style="line-height: 1.5;">
				<p>Upon discharge from the Army where he was "dumb enough to jump out of perfectly good airplanes" in the 18th Airborne Corps, Culler graduated from the University of Alabama and went onto a life in politics, working for candidates and office holders from law enforcement to those with presidential ambitions. A chance encounter while working for the Indiana House of Representatives as a legislative proofreader in 2001 led him, oddly, to racing, initially as a fan. Not being one to sit on the sidelines, Culler got a job walking hots at Santa Anita. "I'd never worked for so little money, had never lived in more spartan conditions, and had never been happier," says Culler about those early days in racing. "I've been blessed to work for some great horsemen. To the average fan, the horses magically appear 15 min before post time, run the race, then disappear. And they are a series of fractions and stats on newsprint in the Form. People have no idea how much work goes into that animal on a daily basis, beginning before 5 a.m. And that's 365 days a year."</p>
				<p>When he's not immersed in race replays or looking for an angle in the upcoming Pick 6, Jeff is a member of the Las Vegas chapter of the University of Alabama Alumni Assoc, devotes his time to local candidates and causes he believes in, and is active in a local French language and cultural club. He hopes to use his French racing contacts to work in France one day.</p>
				<p>Get my picks for the WINNING ticket!</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- Tobler Picks (modal) -->
<div class="modal fade bd-example-modal-lg" id="picksTobler" tabindex="-1" role="dialog" aria-labelledby="picksToblerLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-title" id="picksToblerLabel">
					<div class="section-title mb-10">
						<h6>PICKS</h6>
						<h2>Dennis Tobler</h2>
                    </div>
                  </div>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
			</div>
 			<div class="modal-body">
				<span class="dropcap square large" style="background-color: #FFFFFF; padding: 5px 5px 60px 0px;"><img src="images/TRFDennisHeadshot2.jpg" alt="Dennis Tobler"></span><p style="padding: 0px 0px 5px 4000px;"><h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&mdash;&nbsp;Coming Soon&nbsp;&mdash;</h3></p> <!-- Padding left doesn't work for some reason - fudge it with spaces-->
				<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Email Dennis: <a href="mailto:dennis@thoroughbredforecast.com">dennis@thoroughbredforecast.com</a></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- Culler Picks (modal) -->
<div class="modal fade bd-example-modal-lg" id="picksCuller" tabindex="-1" role="dialog" aria-labelledby="picksCullerLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-title" id="picksCullerLabel">
					<div class="section-title mb-10">
						<h6>PICKS</h6>
						<h2>Jeff Culler</h2>
                    </div>
                  </div>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
			</div>
 			<div class="modal-body">
				<span class="dropcap square large" style="background-color: #FFFFFF; padding: 5px 5px 60px 0px;"><img src="images/JeffCullerOnHorse.jpg" alt="Dennis Tobler"></span><p style="padding: 0px 0px 5px 4000px;"><h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&mdash;&nbsp;Coming Soon&nbsp;&mdash;</h3></p> <!-- Padding left doesn't work for some reason - fudge it with spaces-->
				<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Email Jeff: <a href="mailto:info@thoroughbredforecast.com">info@thoroughbredforecast.com</a></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
*/
?>
<?php
}
?>

<!-- Handicappers -->
<section class="page-section-ptb" style="background: #e5e5e5;">
	<div class="container" style="border-style: none;">
		<div class="row">
			<div class="col-lg-12 col-md-12">
 				<div class="section-title text-center">
					<h2>Meet Our Handicapper<?php echo ($handicappersCnt == 1 ? "" : "s"); ?></h2>
				</div>
			</div>
		</div>

<?php
// Now get the handicappers data again (PDO on MySQL won't allow the reuse of the first result set - no "CURSOR_SCROLL" (FIX THIS ORACLE!)
$rsHandicappers->execute($aHValues);

$count = 1;
while($handicapper = $rsHandicappers->fetch(PDO::FETCH_ASSOC)){
	$fullname = $handicapper['first_name']." ".$handicapper['last_name'];

	if ($count % 2){
		echo "
		<div class='row'>
		";
}
?>
			<div class="col-lg-2" style="font-size: 14px; color: #000000;">
				<a class="popup-modal" data-toggle="modal" href="#handicapper<?php echo $handicapper['moniker']; ?>" title="About <?php echo $fullname; ?>">
				<span style="text-align: center"><img src="images/<?php echo $handicapper['picture']; ?>" alt="<?php echo $fullname; ?>"><br><span style="font-size: 20px"><?php echo $fullname; ?></span></span></a><br><br>
			</div>
			<div class="col-lg-4 sm-mt-30">
				<p.black style="font-size: 20px; color: #000000;"><strong><em><?php echo $handicapper['tagline']; ?></em></strong></p>
				<p><button type="button" class="btn btn-secondary popup-modal" style="width:90%; position:absolute; bottom:50px;" data-toggle="modal" href="#picks<?php echo $handicapper['moniker']; ?>" title="<?php echo $fullname.(substr($fullname, -1) == 's' ? '\'': '\'s'); ?> Picks">Get <?php echo $handicapper['first_name'].(substr($handicapper['first_name'], -1) == 's' ? '\'': '\'s'); ?> Picks</button></p>
			</div>
<?php
	$count++;
	if (($count % 2) != 0){
		echo "
		</div><br>
		";
	}
}
?>
		</div>
	</div>
</section>

<?php
	// Get the videos
	$query = "
		SELECT *
		FROM videos
		WHERE active = :active
		ORDER BY priority ASC, season DESC, show_date DESC
	";
	try{
		$rsVideos = $dbLink->prepare($query);
		$aValues = array(
			':active'		=> "1"
		);
		//$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
		//echo $preparedQuery;die();
		$rsVideos->execute($aValues);
	}
	catch(PDOException $error){
		// Log any error
		file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
	}

?>
<!-- "Show" anchor -->
<a name="show"></a>

<section class="white-bg page-section-ptb">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12">
 				<div class="section-title text-center">
					<h2>Thoroughbred Racing Forecast</h2>
					<h4>Current Season</h4>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 text-center">
				<!-- Videos -->
				<div class="isotope columns-4 popup-gallery">
<?php
	// Define some variable to test against
	$season = "0";
	$switch = true;

	while($video = $rsVideos->fetch(PDO::FETCH_ASSOC)){
		// See if we are displaying the current season or a past season
		if ($video['season'] !== $season && $season !== "0" && $switch){  // Past season!
			// Stop the output, display a new header, then resume thumbnail output
			echo'
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12">
 				<div class="section-title text-center">
					<br>
					<h4>Best of the Past Seasons</h4>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 text-center">
				<!-- Past Season Videos -->
				<div class="isotope columns-4 popup-gallery">

			';
			// Stop testing for season change
			$switch = false;
		}
		$season = $video['season'];

		echo '		<div class="grid-item all">
						<div class="portfolio-item">
							<div class="popup-video-image border-video popup-gallery">
								<img class="img-fluid" src="images/'.$video['thumbnail'].'" alt="">
		';
		if ($video['host'] == "Vimeo"){
			echo '			<a class="popup-vimeo" href="'.$video['link'].'"> <i class="fa fa-play"></i> </a>
			';
		}elseif ($video['host'] == "You Tube"){
			echo '			<a class="popup-youtube" href="'.$video['link'].'"> <i class="fa fa-play"></i> </a>
			';
		}else{
			echo '			<a class="popup-youtube" href="'.$video['link'].'"> <i class="fa fa-play"></i> </a>
								<video style="width:100%;height:100%;" id="player1" poster="images/'.$video['thumbnail'].'" controls preload="none">
									<source type="video/mp4" src="'.$video['link'].'" />
									<source type="video/webm" src="'.$video['link'].'" />
									<source type="video/ogg" src="'.$video['link'].'" />
								</video>
 			';
		}
		echo '					<div class="video-attribute">
									<span class="length">'.$video['length'].'</span>
									<span class="quality">'.$video['quality'].'</span>
								</div>
							</div>
							<div class="blog-info">
								<span class="tag">Thoroughbred Racing Forecast</span>
								<h4 class="mt-10">'.$video['title'].'</h4>
		';
		if (!is_null($video['show_date']) && $video['show_date'] !== "" && $video['show_date'] !== "0000-00-00"){
			if (substr($video['show_date'], -6) == "-00-00"){
				echo '		<span>Air Date: '.substr($video['show_date'], 0, 4).'</span>
				';
			}else{
				echo '		<span>Air Date: '.date('m-d-Y', strtotime($video['show_date'])).'</span>
				';
			}
		}else{
			echo '			<span>&nbsp;</span>
			';
		}
		echo '				</div>
						</div>
					</div>
		';

	}
?>
				</div>
			</div>
		</div>
	</div>
</section>



<!-- END include bodyTRF -->

