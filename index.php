<!DOCTYPE html>
<?php
 header("Content-Type: text/html");
include("include/Header.php");
//get browser language
$languages=array("de", "fr", "it", "en");
if (!isset($_REQUEST["language"])) {
	if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])) {
		$language=substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2);		
		if(!in_array($language, $languages)) {
			$language="de"; //default language is german
		}
	} else {
		$language="de"; 
	}
} else {
	$language=$_REQUEST["language"];
}

?>
<html>
	<head>
	<title>BiUM mobile</title>
	<?php
	include("include/html/header.html");
	?>

	<!--google analytics -->
	<script type="text/javascript">

	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-25668802-1']);
	_gaq.push(['_trackPageview']);

	(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();

	</script>
</head>

<body data-theme="l">

	<div data-role="page" data-add-back-btn="true">
		<div data-role="header" data-theme="l">
			<h1>BiUM mobile</h1>
		</div>
		<div data-role="content">
		
			<b>Catalogue : </b>
			<br/>
			<br/>
		<?php
		//the form is hidden when we display a specific record
		if (!isset($_REQUEST["id"])) {
			echo '<form name="query" action="index.php" method="get">';
			echo '<input type="text" name="q" value="';
			if (isset($_REQUEST["q"])) {
				echo $_REQUEST["q"];
			}
			echo '"/>';
			if (isset($_REQUEST["network"])) {
				$network = $_REQUEST["network"];
			}
			else
				$network = "R81";
			if (isset($_REQUEST["library"])) {
				$library = $_REQUEST["library"];
			}
			else
				$library = "R81081";

			echo '<input type="hidden" name="network" value="'.$network.'"/>';
			echo '<input type="hidden" name="library" value="'.$library.'"/>';
			echo '<input type="hidden" name="libraryfilter" value="on"/>';
			echo '<input type="hidden" name="language" value="'.$language.'"/>';

			echo '<input type="submit" value="'.getMessage("search",$language).'" data-icon="search" data-iconpos="right" />';

//			if (isset($_REQUEST["library"]) && $_REQUEST["library"]!=false) {
//				echo '<fieldset data-role="controlgroup">';
//				echo '<input type="checkbox" name="libraryfilter" id="checkbox-1" class="custom"';
//				if (isset($_REQUEST["libraryfilter"]) and $_REQUEST["libraryfilter"]=="on") {
//					echo "checked";
//				}
//				echo '>';
//				echo '<label for="checkbox-1">'.getLibraryName($_REQUEST["library"]).'</label>';
//				echo '</fieldset>';
//			}
			echo '</form>';
		}


//		if(isset($_REQUEST["libraryfilter"]) ) { 
//			$library=$_REQUEST["library"];	
//		}
//		else
//		{
//			$library=false;
//		}




//		if(isset($_REQUEST["network"])){
//			$network=$_REQUEST["network"];
//		}
//		else{
//			$network=false;
//			
//		}

			
		if (isset($_REQUEST["q"])) {
			//display a list of results

			//set defaults
			if (isset($_REQUEST["offset"])) {
				$offset=$_REQUEST["offset"];
			} else {
				$offset=1;
			}	
			search($_REQUEST["q"], $network, $library, $offset, $language);	
		
		} else if(isset($_REQUEST["id"])) {
			$library=$_REQUEST["library"];
			//display a single item	
			displayItem($_REQUEST["id"], $network, $library, $language);	
		} else {
			$urlRegularSwissbib='http://www.chuv.ch/bdfm/';
//			if ($language!='de') {
//				$urlRegularSwissbib=$urlRegularSwissbib.$language;
//			}
			echo '
			<br/><br/>
			<ul data-role="listview">
			  <li>Horaires d\'ouverture : 8h00 - 22h00 tous les jours</li>

			  <li><a href="http://www.ncbi.nlm.nih.gov/m/pubmed/" rel="external" target="_blank">PubMed mobile</a></li>
			  <li><a href="http://m.webofknowledge.com/" rel="external" target="_blank">Web of Knowledge mobile</a></li>
			  <li><a href="http://www2.unil.ch/perunil/" rel="external" target="_blank">perUnil</a></li>
			  <li><a href="http://www2.unil.ch/ebooks/" rel="external" target="_blank">ebooksUnil</a></li>
			  <li><a href="http://www.bium.ch/wiki" rel="external" target="_blank">BiUM Wiki</a></li>
			  <li><a href="http://www.bium.ch/blog" rel="external" target="_blank">BiUM Blog</a></li>
			  <li><a href="http://www.chuv.ch/bdfm/" rel="external" target="_blank">BiUM site classic</a></li>

			</ul>
			<br />
			<div data-role="controlgroup" data-type="horizontal" align="center">';
				
			foreach ($languages as $lang){
			
				$queryString=$_SERVER["QUERY_STRING"];
				$queryString=preg_replace("/&language=[a-z]{2}/","",$queryString);
				echo '<a href="'.$_SERVER["PHP_SELF"].'?'.$queryString.'&language='.$lang.'" data-role="button"';
				if ($lang == $language) {
					echo ' class="ui-btn-active"';
				}
				echo '>';
				echo $lang;
				echo '</a>';
			}

			echo '</div>';	
//			echo getMessage("about",$language);
			echo '<a href="https://github.com/swissbib/swissbibMobile">';
			echo 'powered by Swissbib Mobile</a>.';
		}


		?>


			
			
		</div><!-- /content -->
	</div><!-- /page -->
</body>
</html>
