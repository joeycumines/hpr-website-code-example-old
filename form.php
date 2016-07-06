<?php
	require('php/template.php');
	
	
?>
<!DOCTYPE html>
<html lang="en">
	<?php echoHead('Form Guide', ''); ?>
	<body>
		<?php echoNav(''); ?>
		<div class="alert alert-danger" role="alert"><strong>DISCLAIMER: </strong>By using this website you agree to the <a href="#" data-toggle="modal" data-target="#t_and_c_m">Terms and Conditions</a> 
			set out by this site, including our Cookie Use.
		<br><br>This application is in early ALPHA stages, if you encounter any problems, or have comments or suggestions, please contact through the Facebook page or Dev Blog.</div>
		<!-- Modal -->
		<div class="modal fade" id="t_and_c_m" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
						<h4 class="modal-title" id="myModalLabel">Terms & Conditions</h4>
					</div>
					<div class="modal-body">
						<?php require('termsAndConditions.html'); ?>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal">I Agree</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		<div class="container" id="metalBackedContainer1">
			<h1>Upcoming Form (2 Days)</h1>
			<h2 id="digiClock" style="color:red;">Race clock (Syd Time): </h2>
			<form>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-btn">
							<input class="btn btn-default" type="submit" value="FROM">
						</span>
						<select class="form-control" name="from-date" id="from-date">
							<?php
								//echo dates from today back 5 date('Y-m-d', strtotime('-1 days'))
								echo('<option value="'.date('Y-m-d', strtotime('-5 days')).'" '.((isset($_GET['from-date']) && date('Y-m-d', strtotime('-5 days')) == $_GET['from-date']) ? 'selected' : '').'>'.date('Y-m-d', strtotime('-5 days')).'</option>');
								echo('<option value="'.date('Y-m-d', strtotime('-4 days')).'" '.((isset($_GET['from-date']) && date('Y-m-d', strtotime('-4 days')) == $_GET['from-date']) ? 'selected' : '').'>'.date('Y-m-d', strtotime('-4 days')).'</option>');
								echo('<option value="'.date('Y-m-d', strtotime('-3 days')).'" '.((isset($_GET['from-date']) && date('Y-m-d', strtotime('-3 days')) == $_GET['from-date']) ? 'selected' : '').'>'.date('Y-m-d', strtotime('-3 days')).'</option>');
								echo('<option value="'.date('Y-m-d', strtotime('-2 days')).'" '.((isset($_GET['from-date']) && date('Y-m-d', strtotime('-2 days')) == $_GET['from-date']) ? 'selected' : '').'>'.date('Y-m-d', strtotime('-2 days')).'</option>');
								echo('<option value="'.date('Y-m-d', strtotime('-1 days')).'" '.((isset($_GET['from-date']) && date('Y-m-d', strtotime('-1 days')) == $_GET['from-date']) ? 'selected' : '').'>'.date('Y-m-d', strtotime('-1 days')).'</option>');
								echo('<option value="'.date('Y-m-d').'" '.((!isset($_GET['from-date']) || (isset($_GET['from-date']) && date('Y-m-d') == $_GET['from-date'])) ? 'selected' : '').'>Today</option>');
								//echo tomorrow
								echo('<option value="'.date('Y-m-d', strtotime('+1 days')).'" '.((isset($_GET['from-date']) && date('Y-m-d', strtotime('+1 days')) == $_GET['from-date']) ? 'selected' : '').'>'.date('Y-m-d', strtotime('+1 days')).'</option>');
							?>
						</select>
					</div><!-- /input-group -->
				</div>
			</form>
			<div class="row">
				<h2>Next 6 Races</h2>
				<div id="nextRaces"><h3><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading content...</h3></div>
			</div>
			<div class="row" id="upcomingTable">
				<h2>This page requires Javascript to be enabled to work.</h2>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12" id="venueRows">
				</div>
			</div>
		</div>
		
		<?php echoFooter(''); ?>
		
		<?php echoJS(); ?>
		<script src="js/form.js"></script>
	</body>
</html>
