if (typeof location.origin === 'undefined')
location.origin = location.protocol + '//' + location.host;

var resultID = "blogRSS";
var url = location.origin+'/php/echoBlogPosts.php';

document.getElementById(resultID).innerHTML = 'LOADING...';

var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() {
	if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		document.getElementById(resultID).innerHTML = xmlhttp.responseText;
		} else if (xmlhttp.readyState == 4) {
		//failed
		document.getElementById(resultID).innerHTML = 'Error loading the Development Blog.';
	}
}
xmlhttp.open("GET", url, true);
xmlhttp.send();
