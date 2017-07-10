// console.log("Navbar.js Connected");

// ******************
// * Click Handlers *
// ******************

// When Search Users is clicked on
$('#searchForm-users').on('click', function() {
	$('#searchTable').val('users');
	$('#searchKeywords').attr("placeholder", "Search Users");
});

// When Another Search is clicked on
$('#searchForm-test').on('click', function() {
	$('#searchTable').val('test');
	$('#searchKeywords').attr("placeholder", "Search Test");
});


// **************
// * Validation *
// **************

// Search Form Validate Function
function searchFormValidate() {
	// Check is Search Input value is null, if so return false (Validation didn't pass)
	return $('#searchKeywords').val() == "" ? false : true;
}

// When Search is clicked Submit Form
$("#searchFormSubmit").on('click', function() {
	if(searchFormValidate()) { // No Errors
		// Submit Form
		document.searchForm.submit();
	} else { // Errors
		// Do some Animation
		$("#searchKeywords").animateCss('flash');
	}
});

// ********************
// * jQuery Exentions *
// ********************

// Extend jQuery for animate.css animation
// Use: $('selector').animateCss('animation');
$.fn.extend({
    animateCss: function (animationName) {
        var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        this.addClass('animated ' + animationName).one(animationEnd, function() {
            $(this).removeClass('animated ' + animationName);
        });
        return this;
    }
});