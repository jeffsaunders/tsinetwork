<!-- START include headerMenus -->

<?php
/*
Header menus for all sites
Copyright 1999-2021, TSI Network, LLC
Authored by Jeff S. Saunders 12/19/2020
Modified by Jeff S. Saunders 04/22/2021
*/

// Define Header Menus per site
switch ($site){

	case "tsi": // TSINetwork.biz
?>
									<li><a href="/">Home</a></li>
									<li><a href="javascript:void(0)">Sites <i class="fa fa-angle-down fa-indicator"></i></a>
										<!-- drop down multilevel  -->
										<div class="drop-down grid-col-6 menu-offset-0">
											<!--grid row-->
											<div class="grid-row">
												<!--grid column 3-->
												<div class="grid-col-6">
													<ul>
														<li><a href="https://footballforecast.com">Football Forecast</a></li>
														<li><a href="https://thoroughbredforecast.com">Thoroughbred Forecast</a></li>
<!--														<li><a href="https://thoroughbredforecast.tsinetwork.biz">Thoroughbred Forecast</a></li>-->
														<li><a href="https://gamblingbroadcast.com">Gambling Broadcast</a></li>
														<li><a href="https://nowplaceyourbets.com">Now Place Your Bets</a></li>
														<li><a href="https://dennistobler.com">Dennis Tobler's Archives</a></li>
<!--														<li><a href="https://dennistobler.tsinetwork.biz">Dennis Tobler's Archives</a></li>-->
														<!--<li><a href="https://www.tsinetwork.biz">TSI Network</a></li>-->
													</ul>
												</div>
											</div>
										</div>
									</li>
									<li><a href="#about">About</a></li>
									<li><a href="#contact">Contact</a></li>

<?php
		break;

	case "dt": // DennisTobler.com
?>
									<li><a href="/">About</a></li>
									<li><a href="#archives">Archives</a></li>
									<li><a href="javascript:void(0)">Sites <i class="fa fa-angle-down fa-indicator"></i></a>
										<!-- drop down multilevel  -->
										<div class="drop-down grid-col-6 menu-offset-0">
											<!--grid row-->
											<div class="grid-row">
												<!--grid column 3-->
												<div class="grid-col-2">
													<ul>
														<li><a href="https://footballforecast.com">Football Forecast</a></li>
														<li><a href="https://thoroughbredforecast.com">Thoroughbred Forecast</a></li>
														<li><a href="https://gamblingbroadcast.com">Gambling Broadcast</a></li>
														<li><a href="https://nowplaceyourbets.com">Now Place Your Bets</a></li>
														<!--<li><a href="https://dennistobler.com">Dennis Tobler's Archives</a></li>-->
														<li><a href="https://www.tsinetwork.biz">TSI Network</a></li>
													</ul>
												</div>
											</div>
										</div>
									</li>
									<li><a href="#contact">Contact</a></li>

<?php
		break;

	case "ffw": // FootballForecast.com
?>

<?php
		break;

	case "trf": // ThoroughbredForecast.com & ThoroughbredRacingForecast.com
?>
									<li><a href="/">About</a></li>
<!--									<li><a href="#archives">Archives</a></li>-->
									<li><a href="#handicappers">Handicappers <i class="fa fa-angle-down fa-indicator"></i></a>
										<!-- drop down multilevel  -->
										<div class="drop-down grid-col-6 menu-offset-0">
											<!--grid row-->
											<div class="grid-row">
												<!--grid column 3-->
												<div class="grid-col-2">
													<ul>
														<li><a href="#handicappers" class="popup-modal" data-toggle="modal">Dennis Tobler</a></li>
<!--														<li><a href="#handicapperCuller" class="popup-modal" data-toggle="modal">Jeff Culler</a></li>-->
<!--														<li><a href="#handicapperXXX" class="popup-modal" data-toggle="modal">A Future Guy</a></li>-->
													</ul>
												</div>
											</div>
										</div>
									</li>
									<li><a href="javascript:void(0)">Sites <i class="fa fa-angle-down fa-indicator"></i></a>
										<!-- drop down multilevel  -->
										<div class="drop-down grid-col-6 menu-offset-0">
											<!--grid row-->
											<div class="grid-row">
												<!--grid column 3-->
												<div class="grid-col-2">
													<ul>
														<li><a href="https://footballforecast.com">Football Forecast</a></li>
														<!--<li><a href="https://thoroughbredforecast.com">Thoroughbred Forecast</a></li>-->
														<li><a href="https://gamblingbroadcast.com">Gambling Broadcast</a></li>
														<li><a href="https://nowplaceyourbets.com">Now Place Your Bets</a></li>
														<li><a href="https://dennistobler.com">Dennis Tobler's Archives</a></li>
														<li><a href="https://www.tsinetwork.biz">TSI Network</a></li>
													</ul>
												</div>
											</div>
										</div>
									</li>
									<li><a href="#contact">Contact</a></li>

<?php
		break;

	case "gb": // GamblingBroadcast.com & GamingBroadcast.com
?>
<?php
		break;

	case "npyb": // NowPlaceYourBets.com
?>
<?php
		break;

	default: // None of the above - render TSI Site menu by default
?>
									<li><a href="/">Home</a></li>
									<li><a href="javascript:void(0)">Sites <i class="fa fa-angle-down fa-indicator"></i></a>
										<!-- drop down multilevel  -->
										<div class="drop-down grid-col-6 menu-offset-0">
											<!--grid row-->
											<div class="grid-row">
												<!--grid column 3-->
												<div class="grid-col-6">
													<ul>
														<li><a href="https://footballforecast.com">Football Forecast</a></li>
														<li><a href="https://thoroughbredforecast.com">Thoroughbred Forecast</a></li>>
<!--														<li><a href="https://thoroughbredforecast.tsinetwork.biz">Thoroughbred Forecast</a></li>-->
														<li><a href="https://gamblingbroadcast.com">Gambling Broadcast</a></li>
														<li><a href="https://nowplaceyourbets.com">Now Place Your Bets</a></li>
														<li><a href="https://dennistobler.com">Dennis Tobler's Archives</a></li>
<!--														<li><a href="https://dennistobler.tsinetwork.biz">Dennis Tobler's Archives</a></li>-->
														<!--<li><a href="https://tsinetwork.biz">TSI Network</a></li>-->
													</ul>
												</div>
											</div>
										</div>
									</li>
									<li><a href="#about">About</a></li>
									<li><a href="#contact">Contact</a></li>


<?php
		break;
}
?>
<!-- END include headerMenus -->
