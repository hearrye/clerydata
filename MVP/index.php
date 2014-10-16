<?php
include_once('include.php');
?>
<script type="text/javascript">
	window.onload = setSuggestionsDiv;
	var sn;
	var ssd;
	
	function setSuggestionsDiv(){
		sn = document.getElementById('school_name');
		ssd = document.getElementById('school_suggestions_div');
	
		ssd.style.width = sn.clientWidth + 'px';
		ssd.style.top = sn.offsetTop + sn.clientHeight + 4 + 'px';
		ssd.style.left = sn.offsetLeft + 'px';
	}
	
	function updateSuggestions(){
		var curText = sn.value;
		
		if (curText.length > 0){
			var oReq = new XMLHttpRequest();
			oReq.onload = handleSuggestions;
			oReq.open('GET', 'school_suggestions?n='+curText, true);
			oReq.send();
		} else {
			ssd.innerHTML = '';
			ssd.style.display = 'none';
			ssd.style.left = sn.offsetLeft + 'px';
		}
	}
	
	function handleSuggestions(){
		ssd.style.display = 'block';
		ssd.innerHTML = '';
		
		var flatSuggestions = this.responseText;
		
		if (flatSuggestions.length > 0){
			var suggestions = flatSuggestions.split("|");
			for (var i = 0; i < suggestions.length; i++){
				ssd.innerHTML += '<div class="suggestedName" onclick="updateSchoolName(\'' + suggestions[i] + '\')">' + suggestions[i] + '</div>'
			}
		} else {
			ssd.innerHTML = '';
			ssd.style.display = 'none';
			ssd.style.left = sn.offsetLeft + 'px';
		}
		
		ssd.style.left = sn.offsetLeft + 'px';
	}
	
	function updateSchoolName(newName){
		sn.value = newName;
		ssd.innerHTML = '';
		ssd.style.display = 'none';
		ssd.style.left = sn.offsetLeft + 'px';
	}
</script>
<form action="school" method="get" autocomplete="off">
<div style="text-align: center; font-size: 1.5em; position: relative; padding-top: 1em; margin-bottom: 2em;">
	Find a school<br />
	<input type="text" id="school_name" name="school_name" oninput="updateSuggestions()" style="font-size: 1em; width: 500px;" autocomplete="off" /> <input type="submit" value="Search" class="genButton" style="font-size: 1em;" />
	<div id="school_suggestions_div" style="position: absolute; border: 1px solid #000000; display: none;"></div>
</div>
</form>

<div style="text-align: center;">
	<a href="clery_rape_reporting.csv" download="clery_rape_reporting.csv">Download raw data</a>
</div>
<?php
fin();
?>