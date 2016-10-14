const config = require('./config');

if ($('#show-usernames').length === 1) {
	$.ajax({
		url: config.API + 'config/show-usernames',
		success: function (response) {
			$('#show-usernames').prop('checked', response === 'true');
		}
	});
}

$('#show-usernames').change(function () {
	let show = $(this).prop('checked');
	$.ajax({
		url: config.API + 'config/show-usernames',
		method: 'post',
		data: {
			value: show
		}
	});
});
