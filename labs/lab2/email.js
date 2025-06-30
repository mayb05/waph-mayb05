var shown = false;
	function showHideEmail(){
		var emailElement = document.getElementById('email');
		if(shown){
			emailElement.innerHTML="Show my email";
			shown = false;
		} else {
			var myemail = "<a href='mailto:mayb5@udayton.edu'>mayb05@udayton.edu</a";
			emailElement.innerHTML=myemail;
			shown=true;
		}
	}