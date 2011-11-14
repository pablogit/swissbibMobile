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
		$itemNetwork=getHoldingField($item,'B');
		$itemLibraryCode=getHoldingField($item,'b');
		if ( (substr($network,0,1)=='R' || $network==false)
			 && $itemNetwork=='RERO'
		) { 
		//address searches in local rero networks, get library and network codes from $itemLibraryCode, i.e. 949$b
		//for rero results, the 949$B is always RERO and not the local network codes
		//the local network is in 949$b, first digit for RERO-FR and first and second digits for other rero networks
		//the library code is in 949$b, digits 1-5 for RERO-FR and digits 1-6 for other rero networks
			if (substr($itemLibraryCode,1,1)=="0") {//Rero Fribourg
				if ($network!='R*') { 
					$itemNetwork='R1'; //i.e. R1
				} else {
					$itemNetwork='R*';
				}
				$itemLibraryCode='R'.substr($itemLibraryCode,0,4);
			} else { //other rero
				if ($network!='R*') {
					$itemNetwork='R'.substr($itemLibraryCode,0,2);
				} else {
					$itemNetwork='R*';
				}
				$itemLibraryCode='R'.substr($itemLibraryCode,0,5);				
			}
		}
		
		
		
		if (   ($itemNetwork==$network && $library==false) 
		    || ($itemLibraryCode==$library && $library==true) 
			|| ($network==false && $library==false) 
		) {//display only items in the network or only library items if checkbox is checked
		
			echo '<li>';
			
			echo '<a href="http://www.swissbib.ch/TouchPoint/perma.do?q=0%3D%22'.$id.'%22+IN+[3]&v=nose&l='.$language.'" rel="external" target="_blank">';
			
						
			echo '<h3>';
			$libraryName=getHoldingField($item,'0');
			
			if ($libraryName!="") {
				echo $libraryName;
			} else if ($itemNetwork=='SNL') { //Swiss National Library
				echo getLibraryName('S1');			
			} else if (substr($itemNetwork,0,1)=='R' || $itemNetwork=='CCSA') {	
				echo getLibraryName($itemLibraryCode);
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
