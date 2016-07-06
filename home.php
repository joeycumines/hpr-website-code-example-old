<?php
	require('php/template.php');
?>
<!DOCTYPE html>
<html lang="en">
	<?php echoHead('Homepage', ''); ?>
	<body>
		<?php echoNav(''); ?>
		<!-- Main jumbotron for a primary marketing message or call to action -->
		<div class="jumbotron">
			<div class="container">
				
				<div class="row">
					<div class="col-sm-4">
						<h1>Why, HPR?</h1>
						<p>
							Never play a game of chance without understanding the odds. Would you gamble your $100 against $50, to win a coin toss?<br> 
							The HPR system evaluates strength of any runners, be they thoroughbred horses, greyhounds, or harness racing, 
							and returns you a probability of winning, similar to pre-flop poker probabilities.
							<br><br>This is no snake oil, no magical secret, just a rating system developed for 
							ranking chess players. As a punter, you are being offered a chance to see for yourself how effective it is. <br>There are no sure things, no such thing 
							as gambling without risk, but with HPR you can gain for yourself a competitive edge, one more consistent and believeable then any other.
						</p>
						<a class="btn btn-primary btn-lg" href="#" role="button">TRY IT TODAY FOR FREE! &raquo;</a>
					</div>
					<div class="col-sm-8">
						<h2>Statistics</h2>
						<p class="inset-box">
							<b>Greyhound Racing&nbsp;</b><b>(All TAB races including overseas)</b>
							<br>
							Data Start: 01/01/2014
							<br>
							Test Start: 20/07/2014
							<br>
							Test End: 05/11/2015
							<br>
							<br>
							"Strike Rate" for predicted most likely to win: <b>29%</b>
							<br>
							"Strike Rate" for the top 6 predicted: <b>90%</b>
							<br>
							Average Number of Runners: <b>7.7</b>
							<br>
							<br>
							<b>Harness Racing&nbsp;</b><b>(All TAB races including overseas)</b>
							<br>
							Data Start: 01/01/2014
							<br>
							Test Start: 20/07/2014
							<br>
							Test End: 05/10/2015
							<br>
							<br>
							"Strike Rate" for predicted most likely to win:&nbsp;<b>28%</b>
							<br>
							"Strike Rate" for the top 6 predicted:&nbsp;<b>83%</b>
							<br>
							Average Number of Runners:&nbsp;<b>9.6</b>
							<br>
							<br>
							<b>Thoroughbred Racing (All TAB races including overseas)</b>
							<br>
							Data Start: 01/01/2014
							<br>
							Test Start: 20/07/2014
							<br>
							Test End: 01/11/2015
							<br>
							<br>
							"Strike Rate" for predicted most likely to win:&nbsp;<b>22%</b>
							<br>
							"Strike Rate" for the top 6 predicted:&nbsp;<b>76%</b>
							<br>
							Average Number of Runners:&nbsp;<b>10.1</b>
						</p>
					</div>
				</div>
				
			</div>
		</div>
		
		<div class="container">
			<div class="row">
				<div id="myCarousel" class="carousel slide" data-ride="carousel">
					<!-- Indicators -->
					<ol class="carousel-indicators">
						<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
						<li data-target="#myCarousel" data-slide-to="1"></li>
						<li data-target="#myCarousel" data-slide-to="2"></li>
						<li data-target="#myCarousel" data-slide-to="3"></li>
					</ol>
					
					<!-- Wrapper for slides -->
					<div class="carousel-inner" role="listbox">
						<div class="item active">
							<div class="container">
								<div class="row">
									<div class="col-xs-2">
									</div>
									<div class="col-xs-8">
										<div class="faq">
											<h2 class="faq">How are the ratings decided?</h2>
											<h3 class="faq">By applying the <a href="http://glicko.net/glicko/glicko2.pdf">"Glicko2"</a> algorithm, developed by Professor Mark E. Glickman, to over a years worth of past data.</h3>
										</div>
									</div>
									<div class="col-xs-2">
									</div>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="container">
								<div class="row">
									<div class="col-xs-2">
									</div>
									<div class="col-xs-8">
										<div class="faq">
											<h2 class="faq">How do you know it works?</h2>
											<h3 class="faq">By assessing factors such as how frequently the winner can be predicted, and by analysing the average "predicted" probability of the winner (winning).</h3>
										</div>
									</div>
									<div class="col-xs-2">
									</div>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="container">
								<div class="row">
									<div class="col-xs-2">
									</div>
									<div class="col-xs-8">
										<div class="faq">
											<h2 class="faq">Can this be used for sports?</h2>
											<h3 class="faq">While not currently available, yes, it can be used for any competition where competitors can be assigned a value representing their "strength" (chance of winning).</h3>
										</div>
									</div>
									<div class="col-xs-2">
									</div>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="container">
								<div class="row">
									<div class="col-xs-2">
									</div>
									<div class="col-xs-8">
										<div class="faq">
											<h2 class="faq">How can I use it?</h2>
											<h3 class="faq">A daily "form guide" system is in development, that will be released daily for upcoming races. Future plans include a interactive web application.</h3>
										</div>
									</div>
									<div class="col-xs-2">
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<!-- Left and right controls -->
					<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
						<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
						<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>
			</div>
		</div> <!-- /container -->
		
		<div class="jumbotron">
			<div class="container">
				<h2>Development Blog</h2>
				<!-- Example row of columns -->
				<div id="blogRSS">
					<!--<div class="col-sm-4">
						<h3>After Hours Service</h3>
						<p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
						<a class="btn btn-default" href="#" role="button">View Full &raquo;</a>
					</div>-->
					Waiting for content..
				</div>
			</div>
		</div>
		
		<?php echoFooter(''); ?>
		
		<?php echoJS(); ?>
		<script src="js/blog.js"></script>
	</body>
</html>
