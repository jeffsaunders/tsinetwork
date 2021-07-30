<!-- BEGIN include bodyDT -->

<?php
/*
Body section content for the Dennis Tobler site
Copyright 1999-2021, TSI Network, LLC
Authored by Jeff S. Saunders 12/19/2020
Modified by Jeff S. Saunders 04/22/2021
*/
?>

<?php
		// Get the collections
		$query = "
			SELECT *
			FROM video_collections
			WHERE active = :active
			ORDER BY `group`, priority ASC
		";
		try{
			$rsCollections = $dbLink->prepare($query);
			$aValues = array(
				':active'		=> "1"
			);
			//$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
			//echo $preparedQuery;die();
			$rsCollections->execute($aValues);
		}
		catch(PDOException $error){
			// Log any error
			file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
		}

		// Stuff collections into an array
		$aCollections = array();
		while($collection = $rsCollections->fetch(PDO::FETCH_ASSOC)){
			// For some reason, the following doesn't work:
//			$aCollections[$collection['uid']] = $collection['name'];
			// ...but this does:
			$x = $collection['uid'];
			$y = $collection['name'];
			$aCollections[$x] = $y;
			/// Go figure............
		}
//echo "<pre>";print_r($aCollections);echo "</pre>";

		// Now get the collections data again (PDO on MySQL won't allow the reuse of the first result set - no "CURSOR_SCROLL" (FIX THIS ORACLE!)
		$rsCollections->execute($aValues);

		// Get the videos
		$query = "
			SELECT *
			FROM videos
			WHERE active = :active
			ORDER BY priority ASC
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
<!-- "Archives" anchor -->
<a name="archives"></a>

<!-- Body -->
<section class="white-bg page-section-ptb">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12">
 				<div class="section-title text-center">
					<h2>The Legacy Archives</h2>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 text-center">
				<!-- Gallery Buttons -->
				<div class="isotope-filters">
					<button data-filter="" class="active">All</button>
<?php
	while($collection = $rsCollections->fetch(PDO::FETCH_ASSOC)){
		echo '	<button data-filter=".'.str_replace(" ", "-", $collection['name']).'"'.($collection['default'] == 1 ? ' class="active"': '').'>'.$collection['name'].'</button>
		';
	}
?>
				</div>
				<!-- Videos -->
				<div class="isotope columns-4 popup-gallery">
<?php
	while($video = $rsVideos->fetch(PDO::FETCH_ASSOC)){
		$aCollTemp = explode('|', $video['collections']);
//print_r($aCollTemp)."<br><br>";
		$sCollections = "";
		foreach($aCollTemp as $key=>$uid){
			$sCollections .= str_replace(" ", "-", $aCollections[$uid])." ";
		}
		// Pop the trailing space off the collections string
		$sCollections = substr($sCollections, 0, -1);
//echo $sCollections."<br>";
		echo '		<div class="grid-item '.$sCollections.'">
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
								<span class="tag">'.$video['show'].'</span>
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

<?php
// NPYB Promo
require("includes/promoNPYB.php");
?>

<!-- END include bodyDT -->
