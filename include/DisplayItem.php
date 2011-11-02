<?php

function displayItem($id, $network=false, $library=false, $language='de')
{
	$sruQuery = new SruQuery();	
	$pxml=$sruQuery->getRecordFromIdWithHoldings($id);	
					
	$marc=$pxml->records->record[0]->recordData->children('srw_marc',true);								
	echo '<h3>';
	echo getMarcField($marc, '245', 'a');
	echo '</h3>';
	echo '<p>';
	echo getMarcField($marc, '245', 'c');
	echo '<br />';
	echo getMarcField($marc, '260', 'a');
	echo '. ';
	echo getMarcField($marc, '260', 'b');
	echo '. ';
	echo getMarcField($marc, '260', 'c');
	echo '<br />';
	echo getMarcField($marc, '250', 'a');
				
	
	
	echo '<br />['.$id.']<br /><br />';
	echo '</p>';				
	echo "\n";
	echo "\n";
	echo '<ul data-role="listview">';	
	echo "\n";
	
	$holdings=$pxml->records->record[0]->extraRecordData->children('http://oclc.org/srw/extraData');
	$holdings=$holdings->children('urn:oclc-srw:holdings');
	$holdings->registerXPathNamespace('ns3', 'urn:oclc-srw:holdings');
	$results=$holdings->xpath('ns3:datafield');

	
	foreach ($results as $item) {
		if (   (getHoldingField($item,'B')==$network && $library==false) 
		    || (getHoldingField($item,'b')==$library && $library==true) 
			|| ($network==false && $library==false) 
		) {//display only item in the network or only librari item if checkbox is checked
		
			echo '<li>';
			
			echo '<a href="http://www.swissbib.ch/TouchPoint/perma.do?q=0%3D%22'.$id.'%22+IN+[3]&v=nose&l='.$language.'" rel="external" target="_blank">';
			
						
			echo '<h3>';
			$libraryName=getHoldingField($item,'0');
			$libraryCode=getHoldingField($item,'b');			
			if ($libraryName!="") {
				echo $libraryName;
			} else if (substr($libraryCode,1,1)=="0") {				
				echo getLibraryName("R".substr($libraryCode,0,4));
			} else {
				
				echo getLibraryName("R".substr($libraryCode,0,5));
			}
			echo '</h3>';
			echo '<p><strong>';
			echo getHoldingField($item,'1');
			
			echo '</strong></p>';
			
			echo '<p>';
			$secondCallNumber=getHoldingField($item,'s');
			if ($secondCallNumber) { 
				echo $secondCallNumber; //2nd call number (to be displayed first)				
				echo ' ';					
			}
						
			echo getHoldingField($item,'j'); //call number
			echo '</p>';
			echo '</a>';
			echo '</li>';
			echo "\n";
		}
	}			
	
	echo '</ul>';			
			
			

}




?>
