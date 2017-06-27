// Make Sure I'm Connected
console.log('Connected');

// Handle the dynamic functionality of the urls
// For more info visit link below
// https://stackoverflow.com/questions/44771951/upon-redirect-of-form-submission-within-iframe-jquery-not-detecting-updated-src/44772165?noredirect=1#comment76526474_44772165
$(document).ready(function() {
    $('iframe#settings-iframe').on('load', function() {
    // var location = this.contentWindow.location.href;
    var location = this.contentWindow.location.href.substr(this.contentWindow.location.href.lastIndexOf('/')+1);
    console.log('location : ', location);
    switch (location) {
        case "iframe-home.php":
        console.log(location);
        activateHome();
        break;
      case "changepassword.php":
        console.log(location);
        activatePassword();
        break;
      case "update.php":
        console.log(location);
        activateName();
        break;
    }
  });
});

// Activate the Settings Home Button
function activateHome() {
	$("#home").addClass('active');
	$("#name").removeClass('active');
	$("#password").removeClass('active');
}
// Activate the Change Password Button
function activatePassword() {
	$("#home").removeClass('active');
	$("#password").addClass('active');
	$("#name").removeClass('active');
}
// Activate the Change Name Button
function activateName() {
	$("#home").removeClass('active');
	$("#password").removeClass('active');
	$("#name").addClass('active');
}

// Add the Click Listeners for the buttons
$("#home").on("click", function() {
	activateHome();
	$("#settings-iframe").attr("src", "iframe-home.php");
});
$("#password").on("click", function() {
	activatePassword();
	$("#settings-iframe").attr("src", "changepassword.php");
});
$("#name").on("click", function() {
	activateName();
	$("#settings-iframe").attr("src", "update.php");
});
