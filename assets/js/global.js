// console.log("Connected Global.js");

// Get the base URL/App Root
var appRoot = location.protocol + '//' + location.host;
// Check if App Root is on Dev Server or Produuction Server
if(appRoot.includes("localhost")) {
	appRoot += '/makoto/';
} else {
	appRoot += '/';
}

// Show the App Root in the Log
// console.log(appRoot);

// On Page Load Events
$(document).ready(function(){

	// Slide down the alerts upon page load
    // $('#alert').slideDown(250);
    $( '#alert' ).removeClass( 'slideOutUp' ).show().addClass( 'flipInX' );

    // Create the Audio Element
    var alertSound = document.createElement('audio');
    var alertDismiss = document.createElement('audio');

    // Debug Info - Is the audio ready to play?
    alertSound.addEventListener("canplay",function(){
    	console.log("Alert Notification Sound");
        console.log("Duration:" + alertSound.duration + " seconds");
        console.log("Source:" + alertSound.src);
        console.log("Status: Ready to play");
    });
    alertDismiss.addEventListener("canplay",function(){
    	console.log("Alert Dismiss Sound");
        console.log("Duration:" + alertDismiss.duration + " seconds");
        console.log("Source:" + alertDismiss.src);
        console.log("Status: Ready to play");
    });

    // If an alert has popped up
    if($('#alert').length) {
    	console.log("Alert is on the DOM");

		// If alert is a "Success" alert
		if($('.alert-success').length) {
			// Set the attribute of the audio element
			alertSound.setAttribute('src', appRoot + 'assets/audio/alert-success.mp3');
			// Play Audio File whenever possible
			alertSound.play();
		}

		// If alert is a "Warning" alert
		if($('.alert-warning').length) {
			// Set the attribute of the audio element
			alertSound.setAttribute('src', appRoot + 'assets/audio/alert-warning.mp3');
			// Play Audio File whenever possible
			alertSound.play();
		}

		// If alert is a "Danger" alert
		if($('.alert-danger').length) {
			// Set the attribute of the audio element
			alertSound.setAttribute('src', appRoot + 'assets/audio/alert-danger.mp3');
			// Play Audio File whenever possible
			alertSound.play();
		}

		// If alert is a "Information" alert
		if($('.alert-info').length) {
			// Set the attribute of the audio element
			alertSound.setAttribute('src', appRoot + 'assets/audio/alert-info.mp3');
			// Play Audio File whenever possible
			alertSound.play();
		}

		// Load Alert Dismiss Tone
		alertDismiss.setAttribute('src', appRoot + 'assets/audio/alert-dismiss.mp3');
		

		// After 6.5 seconds, alert disappear *POOF*
		window.setTimeout(function() {
			// Play Audio File of Alert Dismiss
			alertDismiss.play();
			// Remove Alert
			$( '#alert' ).removeClass( 'flipInX' ).addClass( 'slideOutUp' );
		}, 7500);
    	
    }

});

// End On Page Load Events
