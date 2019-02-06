$('input[type=text]')
	.blur(function() {
		$('.placeholder').removeClass('placeholder--animate');
		$('.border').removeClass('border--animate');
		$('.lb').removeClass('lb--animate');
		checkInput();
	})
	.focus(function() {
		$('.placeholder').addClass('placeholder--animate');
		$('.border').addClass('border--animate');
		$('.lb').addClass('lb--animate');
		checkInput();
	});

function checkInput() {
	if ($('input[type=text]').val()) {
		$('.placeholder').css('display', 'none');
	} else {
		$('.placeholder').css('display', 'visible');
	}
}
var url_string = location.href; //window.location.href
var url = new URL(url_string);
var ccc = url.searchParams.get('url');

// DOM
var body = document.querySelector('body');

function updateClock() {
	// Get date
	var date = new Date();

	// Get hours and minutes
	var hours = date.getHours();
	var minutes = date.getMinutes();

	if (minutes <= 9) {
		minutes = '0' + minutes;
	}
}

// Update every second
setInterval(updateClock, 10);
setInterval(check, 10);

// Check for date or night
function check() {
	// Get date
	var date = new Date();
	// Get hours
	var hours = date.getHours();

	if (hours >= 9 && hours <= 17) {
		// Day Time
		console.log('Day time');

		var elements = [ body ];

		elements.forEach(function(element) {
			element.classList.remove('night');
			element.classList.add('day');
		});
	} else {
		// Night Time
		console.log('Night time');

		var elements = [ body ];

		elements.forEach(function(element) {
			element.classList.remove('day');
			element.classList.add('night');
		});
	}
}
