<?php
/*
 * Created on 25.05.2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
?>
<div class="flash ui-state-error ui-corner-all"  style="margin-bottom: 10px; padding-left: 10px;">
	<p>
		<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
	    <?php echo $message ?>
	</p>
	</div>
<script type="text/javascript" language="javascript">
$(function() {
	
	function CloseFlash(){
		setTimeout(function() {
				$( "#flash, #flash2" ).fadeOut();
		}, 100000 );
	} 

	CloseFlash();
});
</script>