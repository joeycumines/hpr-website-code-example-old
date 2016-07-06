if (typeof location.origin === 'undefined')
location.origin = location.protocol + '//' + location.host;

var timestampDiff = null;
function getSydTime() {
	if (timestampDiff == null) {
		timestampDiff = 0;
		//lets load this with ajax
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (xhttp.readyState == 4 && xhttp.status == 200) {
				var d = new Date();
				d.setSeconds(0);
				var n = d.getTime();
				var s = xhttp.responseText;
				var bits = s.split(/\D/);
				var date = new Date(bits[0], --bits[1], bits[2], bits[3], bits[4]);
				var ts = date.getTime();
				timestampDiff = ts - n;
			}
		};
		xhttp.open("GET", location.origin+"/php/sydtime.php", true);
		xhttp.send();
	}
	var d = new Date();
	var n = d.getTime();
	return new Date(n+(timestampDiff));
}

var $_GET = {};
if(document.location.toString().indexOf('?') !== -1) {
	var query = document.location
	.toString()
	// get the query string
	.replace(/^.*?\?/, '')
	// and remove any existing hash string (thanks, @vrijdenker)
	.replace(/#.*$/, '')
	.split('&');
	
	for(var i=0, l=query.length; i<l; i++) {
		var aux = decodeURIComponent(query[i]).split('=');
		$_GET[aux[0]] = aux[1];
	}
}

var json = null;
var loadQueue = [];

function toTitleCase(str)
{
	return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}

function expandAndStartLoad(idToClick) {
	var butt = $('#'+idToClick);
	var chev = butt.children().last();
	if (chev.hasClass('glyphicon-chevron-down'))
	butt.click();
	
	return true;
}
function toHexString(data) {
	var result = '';
	for (var x = 0; x < data.length; x++) {
		result+=data.charCodeAt(x).toString(16);
	}
	return result;
}

//loads using the json var
function loadTable() {
	var nextRaces = [];
	document.getElementById('upcomingTable').innerHTML = '';
	document.getElementById('upcomingTable').innerHTML += '<table class="table table-bordered"><thead><tr><th>KEY</th></tr></thead><tbody><tr><td class="done">PAST</td></tr><tr><td class="soon">SOON</td></tr><tr><td class="today">TODAY</td></tr><tr><td class="tomorrow">TOMORROW/PREVIOUS</td></tr></tbody></table>';
	for(var groupName in json){
		var tableInner = '';
		var maxNumberCol = 0;
		
		var events = json[groupName];
		var currentVenue = null;
		var tagOpen = false;
		var venueCount = 0;
		for (var x=0; x < events.length; x++) {
			//for every event, we add to the table, then to the div as a row
			//if our venue has changed, open tags, otherwise close tags.
			if (currentVenue != events[x]['Metadata']['metadata']['venue']) {
				if (tagOpen) {
					if (venueCount > maxNumberCol)
					maxNumberCol = venueCount;
					venueCount = 0;
					tableInner+='</tr>';
					tagOpen = false;
				}
				currentVenue = events[x]['Metadata']['metadata']['venue'];
				tableInner+='<tr><td><strong>'+currentVenue+'</strong></td>';
				tagOpen = true;
			}
			//echo a cell containing the time
			var split = events[x]['Metadata']['due'].split("T");
			if (split.length == 1)
			split = events[x]['Metadata']['due'].split(" ");
			var date = new Date(split[0]);
			var rightTime = split[1];
			
			var theClass = '';
			var todaysDate = getSydTime();
			//calculate the minutes between now and then
			var minutesDiff = 0;
			var expandId = 'expand'+toHexString(events[x]['GroupName'])+toHexString(events[x]['Metadata']['metadata']['venue']);
			if(date.setHours(0,0,0,0) != todaysDate.setHours(0,0,0,0)) {
				theClass = 'tomorrow';
				} else {
				todaysDate = getSydTime();
				var timeSplit = rightTime.split(':');
				var timeHours =  parseInt(timeSplit[0]);
				var timeMins = parseInt(timeSplit[1]);
				
				minutesDiff = (timeHours*60 + timeMins) - (todaysDate.getHours()*60 + todaysDate.getMinutes());
				
				if (minutesDiff < 0)
				theClass = 'done';
				else if (minutesDiff <= 60) {
					theClass = 'soon';
					rightTime += '<br>('+minutesDiff+'m away)';
					} else {
					theClass = 'today';
					rightTime += '<br>('+Math.round(minutesDiff/60)+'h away)';
				}
				
				//load the next race info
				//Add at the position that if is smaller then.
				if (minutesDiff >= -1) {
					var insertPos = nextRaces.length;
					for (var y = nextRaces.length-1; y >= 0; y--) {
						if (nextRaces[y]['minutesDiff'] > minutesDiff) {
							insertPos = y;
						}
					}
					//splice it into array at insertPos
					var toInsert = {};
					toInsert['minutesDiff'] = minutesDiff;
					toInsert['name'] = events[x]['Metadata']['metadata']['venue']+' '+events[x]['Metadata']['metadata']['race'];
					toInsert['link'] = '<a href="#form'+events[x]['EventID']+'" onclick="expandAndStartLoad(\''+expandId+'\');">'+rightTime+'</a>';
					toInsert['group'] = groupName;
					nextRaces.splice(insertPos, 0, toInsert);
					if (nextRaces.length > 6) {
						nextRaces.pop();
					}
				}
			}
			
			tableInner+='<td class="'+theClass+'"><a href="#form'+events[x]['EventID']+'" onclick="expandAndStartLoad(\''+expandId+'\');">'+rightTime+'</a></td>';
			venueCount++;
		}
		if (tagOpen) {
			if (venueCount > maxNumberCol)
			maxNumberCol = venueCount;
			venueCount = 0;
			tableInner+='</tr>';
		}
		
		//rows+='<div class="row data-'+groupName+'">'+events[x]['Metadata']['metadata']['venue']+':'+events[x]['Metadata']['metadata']['race']+'</div>';
		var colBuff = '<th>VENUE</th>';
		for (var x = 0; x < maxNumberCol; x++)
		colBuff+='<th>'+(x)+'</th>';
		var groupTitle = toTitleCase(groupName+' racing');
		document.getElementById('upcomingTable').innerHTML += '<h2 id="'+groupName+'">'+groupTitle+'</h2>';
		document.getElementById('upcomingTable').innerHTML += '<div style="overflow:scroll;"><table class="table table-bordered"><thead><tr>'+colBuff+'</tr></thead><tbody>'+tableInner+'</tbody></table></div>';
	}
	//create nextraces element
	var htmlNextRaces = '';
	for (var x = 0; x < nextRaces.length; x++) {
		htmlNextRaces+='<div class="col-xs-4 col-sm-2 col-md-2"><div class="row">'+nextRaces[x]['group']+'</div><div class="row">'+nextRaces[x]['name']+'</div><div class="row">'+nextRaces[x]['link']+'</div></div>';
	}
	document.getElementById('nextRaces').innerHTML = htmlNextRaces;
}

function clickID(idToClick) {
	$('#'+idToClick).click();
}

/**
	Returns the html structure for the event rows.
	NOTE: This will be inserted under the a div for each venue.
*/
function getRowStructure(dueString, raceString, distString, eventId, winners, venueString, fetchedString, idToClick) {
	//Needs:
	//Title (race)
	//Due time
	//Metadata
	//Winners
	//Odds
	
	var split = dueString.split("T");
	if (split.length == 1)
	split = dueString.split(" ");
	var date = new Date(split[0]);
	var rightTime = split[1];
	var todaysDate = getSydTime();
	var displayString = raceString+' AT ';
	if (date.setHours(0,0,0,0) != todaysDate.setHours(0,0,0,0)) {
		//get the date in 24h + dd/mm/yyyy format
		var dd = date.getDate();
		var mm = date.getMonth()+1; //January is 0!
		
		var yyyy = date.getFullYear();
		if(dd<10){
			dd='0'+dd
		} 
		if(mm<10){
			mm='0'+mm
		} 
		displayString += rightTime+' '+dd+'/'+mm+'/'+yyyy;
		} else {
		//get the date/time in 24h + Today
		displayString += rightTime+' Today';
	}
	
	var html = '<div class="row" style="overflow:scroll;"><div class="col-sm-1 minToolButton"><button class="minToolButton" onclick="clickID(\''+idToClick
	+'\');">-</button></div><div class="col-sm-11"><h3 id="form'+eventId+'">'+displayString+' ('+venueString+')'
	+'</h3><h4>Distance: '+distString+'</h4><h4>Last Update: '+fetchedString+'</h4>';
	//table of winners
	if (winners != null && winners.length > 0) {
		html+='<h4>Results</h4><table class="table table-bordered"><thead><tr><th>Place</th><th>Runner</th></tr></thead><tbody>';
		for (var x = 0; x < winners.length; x++) {
			html+='<tr><td>'+(x+1)+'</td><td>'+winners[x]+'</td></tr>';
		}
		html+='</tbody></table>';
	}
	//Table of runners, with name, rating(dev), probability winning, confidence, computed odds we would give, SNAPSHOT actual
	html += '<h4>Form:</h4><table class="table table-bordered"><thead><tr><th>Runner</th><th>Rating/Deviation</th><th>HPR Prob</th><th>HPR Confidence</th><th>HPR Odds</th><th>Fixed Win*</th></tr></thead><tbody id="tbod'+eventId+'">';
	html+='</tbody></table><h5 id="loadingMessage'+eventId+'"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading Content...</h5>';
	html+='<p><i>*These prices are loaded from external sources, and are for reference only. Gamble responsibly.</i></p>';
	html+='</div></div>';
	
	return html;
}

var refreshIds = {};

/**
	Loads the individual race rows from the json.
	Also starts the json which will retrieve the actual race information.
*/
function loadRows() {
	var venueRows = document.getElementById('venueRows');
	venueRows.innerHTML = '';
	//the id for the section is #form<eventid>
	for(var groupName in json) {
		var events = json[groupName];
		for (var x=0; x < events.length; x++) {
			//if we do not have the id group<groupname> then append it and create header
			var groupDiv = document.getElementById('group'+toHexString(groupName));
			if (groupDiv == null) {
				var groupTitle = toTitleCase(groupName+' racing');
				venueRows.innerHTML += '<div id="'+'group'+toHexString(groupName)+'"><div class="row"><h2>'+groupTitle+'</h2></div></div>';
				groupDiv = document.getElementById('group'+toHexString(groupName));
			}
			//if we do not have venue for group, append it to group<groupname><venue>.
			var venueDiv = document.getElementById('group'+toHexString(groupName)+toHexString(events[x]['Metadata']['metadata']['venue']));
			if (venueDiv == null) {
				groupDiv.innerHTML += '<div class="row expandToolRow" id="'+'expand'+toHexString(groupName)+toHexString(events[x]['Metadata']['metadata']['venue'])+'"><h2 class="col-xs-10">'
				+events[x]['Metadata']['metadata']['venue']+' ('+groupName+')</h2><span class="glyphicon glyphicon-chevron-down expandTool" aria-hidden="true"></span></div><div class="venueCont" id="'
				+'group'+toHexString(groupName)+toHexString(events[x]['Metadata']['metadata']['venue'])+'"></div>';
				venueDiv = document.getElementById('group'+toHexString(groupName)+toHexString(events[x]['Metadata']['metadata']['venue']));
				//add the refreshIds record so we can add to loader.
				refreshIds['expand'+toHexString(groupName)+toHexString(events[x]['Metadata']['metadata']['venue'])] = [];
				$('#'+'group'+toHexString(groupName)+toHexString(events[x]['Metadata']['metadata']['venue'])).slideUp(0);
				$( ".expandToolRow" ).unbind().click(function() {
					$(this).next().slideToggle();
					var chev = $(this).children().last();
					
					if (chev.hasClass('glyphicon-chevron-down')) {
						for (var z = 0; z < refreshIds[$(this).attr('id')].length; z++)
						loadQueue.push(refreshIds[$(this).attr('id')][z]);
					}
					
					chev.toggleClass('glyphicon-chevron-down');
					chev.toggleClass('glyphicon-chevron-up');
				});
			}
			//add so we refresh on down
			refreshIds['expand'+toHexString(groupName)+toHexString(events[x]['Metadata']['metadata']['venue'])].push(events[x]['EventID']);
			//append to venue div
			venueDiv.innerHTML+=getRowStructure(events[x]['Metadata']['due'], events[x]['Metadata']['metadata']['race'],
			events[x]['Metadata']['metadata']['distance'], events[x]['EventID'], events[x]['Metadata']['results'], 
			events[x]['Metadata']['metadata']['venue'], events[x]['Metadata']['fetched'], 'expand'+toHexString(groupName)+toHexString(events[x]['Metadata']['metadata']['venue']));
			
			//add to load queue
			//loadQueue.push(events[x]['EventID']); we do on dropdown open now
		}
	}
}

function calcRunnerSimPerc(event1, event2) {
	var possible = event1['Metadata']['runners'].length;
	if (event2['Metadata']['runners'].length < possible)
	possible = event2['Metadata']['runners'].length;
	var actual = 0;
	
	for (var x = 0; x < event1['Metadata']['runners'].length; x++) {
		for (var y = 0; y < event2['Metadata']['runners'].length; y++) {
			if (event1['Metadata']['runners'][x] == event2['Metadata']['runners'][y]) {
				actual++;
				break;
			}
		}
	}
	return parseFloat(actual)/parseFloat(possible);
}

/**
	If, for any given venue, a event matches > 40% of the runners, we remove the older (fetched).
*/
function stripDuplicatedEvents() {
	var venueNames = {};
	for (var groupName in json) {
		var events = json[groupName];
		for (var x=0; x < events.length; x++) {
			if (!(events[x]['Metadata']['metadata']['venue'] in venueNames)) {
				venueNames[events[x]['Metadata']['metadata']['venue']] = [];
			}
			venueNames[events[x]['Metadata']['metadata']['venue']].push(events[x]);
		}
	}
	var toRemove = [];
	for (var venueName in venueNames) {
		var events = venueNames[venueName];
		for (var x = 0; x < events.length; x++) {
			for (var y = x+1; y < events.length; y++) {
				if (calcRunnerSimPerc(events[x], events[y]) > 0.4) {
					var mins1 = parseInt(events[x]['Metadata']['fetched'].split('T')[1].split(':')[0])*60 + parseInt(events[x]['Metadata']['fetched'].split('T')[1].split(':')[1]);
					var mins2 = parseInt(events[y]['Metadata']['fetched'].split('T')[1].split(':')[0])*60 + parseInt(events[y]['Metadata']['fetched'].split('T')[1].split(':')[1]);
					
					//add on the day parts
					mins1+=parseInt(events[x]['Metadata']['fetched'].split('T')[0].split('-')[2])*60*24;
					mins2+=parseInt(events[y]['Metadata']['fetched'].split('T')[0].split('-')[2])*60*24;
					
					//add on the month parts
					mins1+=parseInt(events[x]['Metadata']['fetched'].split('T')[0].split('-')[1])*60*24*31;
					mins2+=parseInt(events[y]['Metadata']['fetched'].split('T')[0].split('-')[1])*60*24*31;
					
					//add on the year parts
					mins1+=parseInt(events[x]['Metadata']['fetched'].split('T')[0].split('-')[0])*60*24*31*12;
					mins2+=parseInt(events[y]['Metadata']['fetched'].split('T')[0].split('-')[0])*60*24*31*12;
					
					//console.log(events[x]['Metadata']['fetched']+'='+mins1+' VS '+events[y]['Metadata']['fetched']+'='+mins2);
					if (mins1 > mins2) { //x's time is after y's
						toRemove.push(events[y]['EventID']);
						} else {
						toRemove.push(events[x]['EventID']);
					}
				}
			}
		}
	}
	//remove all the eventIds in toRemove, from json
	for(var groupName in json) {
		var events = json[groupName];
		for (var x=0; x < events.length; x++) {
			for (var y = 0; y < toRemove.length; y++) {
				if (events[x]['EventID'] == toRemove[y]) {
					json[groupName].splice(x, 1);
					x--;
					break;
				}
			}
		}
	}
}

function loadForm() {
	document.getElementById('upcomingTable').innerHTML = '<h2><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</h2>';
	//load the content
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (xhttp.readyState == 4 && xhttp.status == 200) {
			json = JSON.parse(xhttp.responseText);
			stripDuplicatedEvents();
			loadTable();
			loadRows();
			} else if (xhttp.readyState == 4) {
			document.getElementById('upcomingTable').innerHTML = '<h2>Error loading the content!</h2>';
		}
	};
	var theDateWeGet = document.getElementById('from-date');
	if (theDateWeGet != null) {
		theDateWeGet = theDateWeGet.options[theDateWeGet.selectedIndex].value;
	} else 
		theDateWeGet='';
	xhttp.open("GET", location.origin+"/php/formContent.php?from-date="+theDateWeGet, true);
	xhttp.send();
}

function updateClock() {
	var n = getSydTime();
	var split = n.toString().split(' ');
	document.getElementById('digiClock').innerHTML = 'Race clock: '+split[0]+' '+split[1]+' '+split[2]+' '+split[3]+' '+split[4]+' Syd Time';
}

updateClock();
var clockTimer = setInterval(function() {
	updateClock();
}, 500);

//START of the actual script
$(document).ready(function(){
	loadForm();
	var tableTimer = setInterval(function() {
		if (json != null) {
			loadTable();
		}
	}, 10000);
});

var isLoading = false;

function loadTableBodyForEventId(eventId) {
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (xhttp.readyState == 4)
		isLoading = false;
		if (xhttp.readyState == 4 && xhttp.status == 200) {
			var comps = JSON.parse(xhttp.responseText);
			document.getElementById('tbod'+eventId).innerHTML='';
			for (var x = 0; x < comps.length; x++) {
				//we add to table rows
				var sample = 'unavailable';
				if ('sampleFixedWin' in comps[x]['Metadata']) {
					sample = Math.round(parseFloat(comps[x]['Metadata']['sampleFixedWin'])*100)/100;
					if (String(sample).split('.').length == 1)
					sample = sample+'.0';
				}
				document.getElementById('tbod'+eventId).innerHTML+='<tr><td>'+comps[x]['CompetitorName']+'</td><td>'+Math.round(parseFloat(comps[x]['Metadata']['rating']))+' / '+Math.round(parseFloat(comps[x]['Metadata']['deviation']))+'</td><td>'+Math.round(parseFloat(comps[x]['Metadata']['prob'])*100)+'%</td><td>'+Math.round(parseFloat(comps[x]['Metadata']['confidence'])*100)+'%</td><td>'+(Math.round(1/(parseFloat(comps[x]['Metadata']['prob'])*parseFloat(comps[x]['Metadata']['confidence']))*100)/100)+'</td><td>'+sample+'</td></tr>';
			}
			
			document.getElementById('loadingMessage'+eventId).innerHTML = '';
			} else if (xhttp.readyState == 4) {
			document.getElementById('loadingMessage'+eventId).innerHTML = 'ERROR LOADING DATA';
		}
	};
	xhttp.open("GET", location.origin+"/php/formcontentevent.php?id="+encodeURIComponent(eventId), true);
	xhttp.send();
}

var loadQueueTimer = setInterval(function() {
	//pop the next one off the queue
	if (isLoading == false && loadQueue.length > 0) {
		isLoading = true;
		loadTableBodyForEventId(loadQueue[0]);
		loadQueue.shift();
	}
}, 200);
