if (typeof location.origin === 'undefined')
	location.origin = location.protocol + '//' + location.host;


function runCommand(tableId, form) {
	var commandUrl = location.origin+"/api.php?command="+encodeURIComponent(form.command.value);
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			document.getElementById(tableId).innerHTML = '<tr><td>'+form.command.value+'</td><td>'+xmlhttp.responseText+'</td></tr>' + document.getElementById(tableId).innerHTML;
			form.command.value = '';
		}
	}
	xmlhttp.open("GET", commandUrl, true);
	xmlhttp.send();
	return false;
}