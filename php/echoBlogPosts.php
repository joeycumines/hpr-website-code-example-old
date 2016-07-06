<?php
	$blogJSONUrl = 'https://ajax.googleapis.com/ajax/services/feed/load?v=1.0&num=10&q=http%3A%2F%2Fhighperformanceratings.blogspot.com%2Ffeeds%2Fposts%2Fdefault%3Falt%3Drss';
	$json = json_decode(file_get_contents($blogJSONUrl), true);
	
	if (empty($json['responseData']) || $json['responseData'] == 'null')
	die();
	$maxPosts = 8;
	$rowCounter = 0;
	$haveClosed = true;
	foreach ($json['responseData']['feed']['entries'] as $entry) {
		$rowCounter++;
		if ($rowCounter == 1) {
			$haveClosed = false;
			echo('
				<div class="row">
');
		}
		echo('
					<div class="col-sm-3">
						<h3>'.$entry['title'].'</h3>
						<h4>'.$entry['publishedDate'].'</h4>
						<p>'.$entry['contentSnippet'].'</p>
						<a class="btn btn-default" href="'.$entry['link'].'" role="button">View Full &raquo;</a>
					</div>
');
		if ($rowCounter == 4) {
			echo('
				</div>
');
			$rowCounter = 0;
			$haveClosed = true;
		}
		$maxPosts--;
		if ($maxPosts <= 0)
			break;
	}
	
	if (!$haveClosed)
		echo('
				</div>
');
?>