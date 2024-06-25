<?php 
function log_1($log) {echo "<script>console.info('$log');</script>";}
date_default_timezone_set('GMT');

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once($_SERVER["DOCUMENT_ROOT"]  . "/" . "functions/translate.php");

$ajax_file = "ajax/disp_tiz_ajax.php";


$numgames = intval($_REQUEST['n'] );
$option   = floatval( $_REQUEST['opt'] );
$line     = floatval( $_REQUEST['line'] );

if(     @intval( $_SESSION['tizer_numgames']) != $numgames
    || @floatval( $_SESSION['tizer_opt'] ) != $option
    || @floatval( $_SESSION['tizer_line'] ) != $line )
{
	include('settings.php');
	$bs = new BetSlip('tizer');
	$bs->createNewTizer($numgames,$option,$line);

	$_SESSION['tizer_numgames'] = $numgames;
	$_SESSION['tizer_opt']      = $option;
	$_SESSION['tizer_line']     = $line;
}

 
?>


<!DOCTYPE html>
<html>
<head>
<?php include('inc/head.php'); ?>

<script>
var betslipmode = 'tizer';
var tizbetsnum  =  <?php echo $numgames; ?>; 
</script>

</head>
<body>
<header> 
	<?php include("inc/header.php"); ?>
  
</header>

	<div class="top-section">
      <div class="main_block main_block-soccer">
        <div class="loading">
      
      
      <div style="color:yellow; margin:auto; width:955px; text-align:center;margin-top:200px; font-size:24px; "><img src="./images/logo.png" alt="c2bet.com"></div>
      <div style="color:yellow; margin:auto; width:955px; text-align:center;"> <i class="fa fa-spinner  fa-spin fa-2x"></i> </div>
      
      
      
      
    </div>
       
		<?php 

		include($ajax_file);


		?>

		</div>
		
<div class="side_block">
			
<?php

        include("inc/side_menu.php");
		 ?>

			
		</div>

	  </div>

<?php include('inc/footer.php') ?>


<script>
/*
var betslipmode = 'tizer';

var hide_ids = new Array();

function opt($id_e)  {
 
  $(".show."+$id_e).html($(".show."+$id_e).text() == "<?php echo trans('Hide option', $dic); ?>" ? "<?php echo trans('Show option', $dic); ?>" : "<?php echo trans('Hide option', $dic); ?>");
  
  $( ".tbl2-ln4."+$id_e ).toggle();
  
  var is_found = false;
  
  for(i = 0; i < hide_ids.length; i = i + 1)
  {
	if(hide_ids[i] == $id_e)
	{
		is_found = true;
		hide_ids.splice(i, 1);
		break;
	}
  }
  
  if(!is_found)
	hide_ids.push($id_e);
}

function ajax()


{

<?php

$sport = lcfirst($sport);

 $ajax_file .= "?sport=".$sport."&time=".$time;

?>


$.ajax({
  type: "GET",
  url: "<?php echo $ajax_file;  ?>",
  data: {sport: "<?php echo $sport;  ?>", liga: "<?php echo $liga_ajax;  ?>"},
  cache: false,
//  async: false,
})
  .done(function( data ) {
    $( ".main_block.main_block-soccer" ).html( data);
        $(".regular-league-holde").show();

	
   for(i = 0; i < hide_ids.length; i = i + 1)
   {
		$( ".tbl2-ln4."+hide_ids[i]).hide();
		$(".show."+hide_ids[i]).html('Show Option');
   }
    
  })

.fail(function() {
   ajax();
  })

.always(function() {
      
  })
  ;

}




 
var $count = 16;
setInterval(function(){

$count -= 1;
$( ".count" ).html($count);

function time_rest()  {$count = 0;}
if ($count == 0)  {update_line();}



}, 1000);


function update_line()   {

       $( ".fa.fa-refresh" ).addClass( "fa-spin" );
       $( ".count" ).addClass( "hide" );
       $count = 18;
       ajax();


} 
        setInterval(function(){
       
betslip_update();

}, 3000);

//////

*/
</script>

</body></html>
