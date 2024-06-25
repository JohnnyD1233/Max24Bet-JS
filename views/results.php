<?php
date_default_timezone_set('GMT');

error_reporting(E_ALL);
ini_set('display_errors', 1);


global $dic;

session_start();
require 'auth_user.php';

require_once($_SERVER["DOCUMENT_ROOT"]  . "/" . "inc/database.php");
require_once($_SERVER["DOCUMENT_ROOT"]  . "/" . "functions/translate.php");

define('RES_POS_EVENT',0);
define('RES_POS_LEAGUE',1);
define('RES_POS_FH',2);
define('RES_POS_FT',3);
define('RES_POS_STATUS',4);


if( isset( $_GET['cmd'] ) &&  $_GET['cmd'] == 'get')
{

   $response = array();	
   $response['data'] = array();


   $sport = strtolower( $_GET['sport'] );
   if( !in_array( $sport, array('soccer','basketball','tennis')) )
		$sport = 'soccer';	

   $day = $_GET['date'];
   if( preg_match('!(\d{2})/(\d{2})/(\d{4})!',$day,$m) )
   {
   	$history_date = sprintf('%02d-%02d-%04d',$m[2],$m[1],$m[3]); 
   }

   if( isset( $history_date ) )
   {

   	$results = $db->prepare("SELECT Sport,Liga,Status,HomeTeam,AwayTeam,FH,FS,FHH,FHA,FSH,FSA,FROM_UNIXTIME(KT,'%d-%m-%Y') as event_date  FROM `result_history`  
				WHERE Sport=:sport HAVING event_date=:eventdate 
				UNION SELECT Sport,Liga,Status,HomeTeam,AwayTeam,FH,FS,FHH,FHA,FSH,FSA,FROM_UNIXTIME(KT,'%d-%m-%Y') as event_date FROM `result`
				WHERE Sport=:sport  HAVING event_date=:eventdate");
        $results->execute(array(':sport'=>$sport,':eventdate'=>$history_date));
   }
   else
   {	
   	$results = $db->prepare("SELECT * FROM `result` WHERE `Sport` = ?");
   	$results->execute(array($sport));
   }	

   $leagues = array();
   $events  = array();

   $result = $results->fetchAll(PDO::FETCH_ASSOC);
   foreach ($result as $res) {

   	$item = array();
	$event_name = sprintf('%s '. trans('vs',$dic) .' %s', trans( $res['HomeTeam'], $dic ), trans( $res['AwayTeam'], $dic ) );
	
	if( !in_array($event_name,$events) )
			$events[] = $event_name;


	$league = trans( $res['Liga'], $dic);	
        if( !in_array($res['Liga'],$leagues) )
                        $leagues[] = $league;

        $item[RES_POS_EVENT] 	= $event_name;
	$item[RES_POS_LEAGUE] 	= $league;
	if( $sport == 'soccer' )
	{
		$item[RES_POS_FH]	= $res['FH'];
		$item[RES_POS_FT]       = $res['FS'];
	}else {
		$item[RES_POS_FH] = sprintf('%s - %s',$res['FHH'],$res['FHA']);
		$item[RES_POS_FT] = sprintf('%s - %s',$res['FSH'],$res['FSA']);
	}
		$item[RES_POS_STATUS]   = trans( $res['Status'], $dic );

	$response['data'][] =  $item; 

  }

   $response['leagues'] = $leagues;
   $response['events']  = $events;		

   header('Content-Type: application/json');
   echo json_encode( $response );
   exit(1);
}


?>

<!DOCTYPE html>

<html data-ng-app="demoApp">

<head>


<?php include('inc/head.php') ?>


<script src="js/jquery.dataTables.min.js"></script>


<style>
.resultsLoading .dataTables_empty{
	display:none;
}
.resultsLoading .dataTables_loading{
	display:block;
}

.resultsReady .dataTables_loading{
	display:none;
}

.dataTables_filter {
   display: none;
}

</style>

</head>
<body>

<header>

<?php include("inc/header.php"); ?>

</header>
 
   <section class="top-section">
      <div class="main_block">
    
	<!--results table-->
			<div class="regular-league-bar"> 
      <div class="list-leagues-ttl"><?php echo trans('Results',$dic); ?></div>

      
      </div>

	<div id="betlist">
  
               <table width="955" border="0" cellpadding="10" cellspacing="0.5" class="bet-list-table-parent">
               <tbody class="betlist_table">
                  
                  <tr>
                     
		    <!--form id="selectorForm"-->
                     <th style="text-align:right;"><?php echo trans('Date',$dic); ?>:<input type="text" name="date" id="datepicker" onChange="reloadData()" placeholder="Today">
                      
                     &nbsp;&nbsp;<?php echo trans('Sport',$dic); ?>&nbsp; 
                     <select name="sport" id="filterSport" onChange="reloadData()">
      			<option value="Soccer" selected="selected"><?php echo trans('Soccer',$dic); ?></option>
      			<option value="Basketball"><?php echo trans('Basketball',$dic); ?></option>
      			<option value="Tennis"><?php echo trans('Tennis',$dic); ?></option>
    		     </select>
                     <!--/form-->
                     
         &nbsp;&nbsp;            
                     
      <?php echo trans('League',$dic); ?>&nbsp;<select id="filterLeagues" name="number" id="number" onChange="applayFilter(1,this.value)">
    </select>               
                      
                     
         &nbsp;&nbsp;<?php echo trans('Event',$dic); ?>&nbsp;
              
      <select id="filterEvents" name="number" id="number" onChange="applayFilter(0,this.value)">
      </select>  
                     
                     
                     
                     
                    <div id="resultsTable_filter"></div> 
                     
                     
                     
                     
                     
                     
                     
                     
                     
                     
                     
                     
                     
                     
                     
                     
                     
                     
                     </th>
             
                  </tr>
                  
                  
       
                  </tbody>
            </table>
            
            
            
            <div data-ng-controller="SimpleController" style="font-size:13px;">
            
             
             <table width="955" border="0" cellpadding="10" cellspacing="0.5" class="bet-list-table-parent restable" id="resultsTable">
                 <thead style="background:rgb(45, 50, 66); color: #fff;"> 
                  <tr>
                     
                     <th><?php echo trans('Event',$dic); ?></th>
                      <th><?php echo trans('League',$dic); ?></th>
                     <th><?php echo trans('First Half',$dic); ?></th>
                     <th><?php echo trans('Full Time',$dic); ?></th>
                     <th><?php echo trans('Status',$dic); ?></th>
                     
                  </tr>
		   <tr class="dataTables_loading"><td colspan="5"><img src="/images/refresh_anim.gif" />&nbsp;<?php echo trans('Loading',$dic); ?></td></tr>
                  </thead>
                   
                   
                    <tbody class="betlist_table">
		    </tbody>
                    </table>
            
            </div>
            
            
            
            
            
            
            
            
            
            
            
            

</div>

			
			<!--results table-->
			
			
			
			
			
			
   
   
   
   
   
   
   
   
    
    


    </div>
      <div class="side_block">
        <?php

        include("inc/side_menu.php");
       ?>
      </div>
   </section>


<?php include('inc/footer.php') ?>

<script>

var resultsTable = null;
var selectEvents = null;
var selectSport  = null;
var selectLeague = null;
var sport = 'Soccer';

var filters = [null,null,null,null];


$( document ).ready(function() {


	selectEvents = $('#filterEvents');
	selectLeague = $('#filterLeagues');
	selectSport = $('#filterSport');
	
	resultsTable = $('#resultsTable').DataTable({
		"ajax": {
  			"dataSrc": function ( json ) {
				createOptions( selectEvents, json.events );
				createOptions( selectLeague, json.leagues );
				$('#resultsTable').switchClass('resultsLoading','resultsReady');
				return json.data;
			}	
		},
  		"bPaginate": false,
    		"bLengthChange": false,	
        	"pageLength": 500,
	    	"language": {"url": "//cdn.datatables.net/plug-ins/f2c75b7247b/i18n/Hebrew.json"},
    	});
	
	/*
	function reloadTable(sport)
	{
		//resultsTable.fnClearTable();
		var resultsdate = $( "#datepicker" ).val();
		var sport = $( "#filterSport option:selected" ).text();	
		resultsTable.ajax.url( '/results.php?cmd=get&sport='+sport ).load();
	}
  	*/

	$( "#datepicker" ).datepicker();


	$( '#selectorForm' ).submit(function(e){
		e.preventDefault();
    		var form = this;
		var sport = selectSport.val();
		reloadTable( sport );
		return false;	
	});


	reloadTable('soccer');

function reloadData()
{
        var sport = selectSport.val();
        reloadTable( sport );
}


});


function reloadData()
{
        var sport = $( "#filterSport option:selected" ).text();
	reloadTable( sport );
}

function reloadTable(sport)
{
      //resultsTable.clear().draw();	
      //resultsTable.dataTable().fnClearTable();	
      var resultsdate = $( "#datepicker" ).val();
      var sport = $( "#filterSport option:selected" ).text();
      $('#resultsTable').switchClass('resultsReady','resultsLoading');
      resultsTable.ajax.url( '/results.php?cmd=get&sport='+sport+'&date='+resultsdate ).load();
      resultsTable.clear().draw();	
}



function createOptions(sel, opt)
{

	if( sel == null || opt == null )
				return;

	sel.find('option').remove(); 
	sel.append( $('<option />').attr('value','').text('<?php echo trans('Select',$dic); ?>') );
	$.each(opt, function(k,v){
	  sel.append($("<option></option>")
         	.attr("value",v)
         	.text(v)); 
	});
}

function applayFilter(field,value)
{
	if(value == '')
		filters[field] = null;
	else	
		filters[field] = value;
	resultsTable.draw();
}

$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {	

	if( filters[0] != null && data[0] != filters[0] )	return false;
	if( filters[1] != null && data[1] != filters[1] )       return false;

	return true; 
    }
);	 
	


</script>


</body>
</html>
