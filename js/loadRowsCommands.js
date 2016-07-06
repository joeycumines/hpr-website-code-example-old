if (typeof location.origin === 'undefined')
location.origin = location.protocol + '//' + location.host;

window.setInterval(function(){
	updateCommands();
}, 3000);

function updateCommands() {
	var tabId = 'commandRows';
	//document.getElementById(tabId).innerHTML = 
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById(tabId).innerHTML = xmlhttp.responseText;
			} else if (xmlhttp.readyState == 4) {
			//failed
			document.getElementById(tabId).innerHTML = 'Error updating the table!';
		}
	}
	xmlhttp.open("GET", location.origin+"/echoTasksTable.php", true);
	xmlhttp.send();
}
