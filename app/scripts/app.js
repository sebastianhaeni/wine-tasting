const API = '/api/v1/';
const IMG = '/upload/';

if ($('#welcome-page').length === 1 && localStorage.getItem('idUser') !== null) {
	window.location = 'voting.html';
	exit;
}

$('#user-form').submit(function () {
	let idUser = guid();
	$.ajax({
		url: API + 'user/register',
		method: 'POST',
		data: {'name': idUser},
		success: function (response) {
			localStorage.setItem('idUser', response.id);
			window.location = 'rules.html';
		}
	});
	return false;
});

if ($('#show-usernames').length === 1) {
	$.ajax({
		url: API + 'config/show-usernames',
		success: function (response) {
			$('#show-usernames').prop('checked', response === 'true');
		}
	});
}

$('#show-usernames').change(function () {
	let show = $(this).prop('checked');
	$.ajax({
		url: API + 'config/show-usernames',
		method: 'post',
		data: {
			value: show
		}
	});
});

if ($('#wine-list').length === 1) {
	$.ajax({
		url: API + 'wine',
		success: function (response) {
			$.each(response, function (i, wine) {
				addWine(wine);
			});

			$.ajax({
				url: API + 'user/' + localStorage.getItem('idUser') + '/votes',
				success: function (response) {
					if (response.vote1 !== null) {
						$('.wine[data-id=' + response.vote1 + '] .btn[data-weight=1]').addClass('active btn-primary');
					}
					if (response.vote2 !== null) {
						$('.wine[data-id=' + response.vote2 + '] .btn[data-weight=2]').addClass('active btn-primary');
					}
					if (response.vote3 !== null) {
						$('.wine[data-id=' + response.vote3 + '] .btn[data-weight=3]').addClass('active btn-primary');
					}
				},
				error: function () {
					localStorage.removeItem('idUser');
					window.location = '/';
				}
			});

			$.ajax({
				url: API + 'user/' + localStorage.getItem('idUser') + '/tasted',
				success: function (response) {
					$.each(response, function (i, wineId) {
						$('.wine[data-id=' + wineId + '] .tasted').prop('checked', true);
					});
				},
				error: function () {
					localStorage.removeItem('idUser');
					window.location = '/';
				}
			})
		}
	});
}

if ($('#results').length === 1) {
	$.ajax({
		url: API + 'config/show-usernames',
		success: function (response) {
			let showUsername = response === 'true';
			$.ajax({
				url: API + 'wine/ranking',
				success: function (response) {
					$.each(response, function (i, wine) {
						addResultWine(wine, showUsername);
					});
				}
			});
		}
	});

	setTimeout(updateResults, 4000);
}

function updateResults() {

	$.ajax({
		url: API + 'wine/ranking',
		success: function (response) {
			$.each(response, function (i, wine) {
				$('.wine[data-id=' + wine.id + ']').attr('data-points', wine.points);
				$('.wine[data-id=' + wine.id + '] .points').text(wine.points);
			});
			$('.wine').snapshotStyles();
			tinysort('.wine', {attr: 'data-points', order: 'desc'});
			$('.wine').releaseSnapshot();
		}
	});

	setTimeout(updateResults, 2000);
}

const stylesToSnapshot = ["transform", "-webkit-transform"];

$.fn.snapshotStyles = function () {
	if (window.getComputedStyle) {
		$(this).each(function () {
			for (let i = 0; i < stylesToSnapshot.length; i++)
				this.style[stylesToSnapshot[i]] = getComputedStyle(this)[stylesToSnapshot[i]];
		});
	}
	return this;
};

$.fn.releaseSnapshot = function () {
	$(this).each(function () {
		this.offsetHeight; // Force position to be recomputed before transition starts
		for (let i = 0; i < stylesToSnapshot.length; i++)
			this.style[stylesToSnapshot[i]] = "";
	});
};

function createListStyles(rulePattern, rows, cols) {
	let rules = [], index = 0;
	for (let rowIndex = 0; rowIndex < rows; rowIndex++) {
		for (let colIndex = 0; colIndex < cols; colIndex++) {
			let x = (10 + (colIndex * 110)) + "%",
				y = (10 + (rowIndex * 110)) + "%",
				transforms = "{ -webkit-transform: translate3d(" + x + ", " + y + ", 0); transform: translate3d(" + x + ", " + y + ", 0); }";
			rules.push(rulePattern.replace("{0}", ++index) + transforms);
		}
	}
	let headElem = document.getElementsByTagName("head")[0],
		styleElem = $("<style>").attr("type", "text/css").appendTo(headElem)[0];
	if (styleElem.styleSheet) {
		styleElem.styleSheet.cssText = rules.join("\n");
	} else {
		styleElem.textContent = rules.join("\n");
	}
}

createListStyles(".wine-result:nth-child({0})", 50, $(window).width() / 420);

$(document).on('click', '.vote', function () {
	let addVote = !$(this).hasClass('active');
	$(this).toggleClass('active btn-primary');
	let weight = parseInt($(this).attr('data-weight'));
	let idWine = $(this).parents('.wine').attr('data-id');

	$('.vote[data-weight=' + weight + '].active').not(this).each(function (i, vote) {
		$(vote).removeClass('active btn-primary');
		changePoints($(vote).parents('.wine').attr('data-id'), -weight);
	});

	$('.wine[data-id=' + idWine + '] .active').not(this).each(function (i, vote) {
		$(vote).removeClass('active btn-primary');
		let thisWeight = parseInt($(this).attr('data-weight'));
		changePoints($(vote).parents('.wine').attr('data-id'), -thisWeight);

		$.ajax({
			url: API + 'wine/vote' + thisWeight,
			method: 'POST',
			data: {
				idUser: localStorage.getItem('idUser'),
				idWine: null
			}
		});
	});

	changePoints(idWine, addVote ? weight : -weight);

	$.ajax({
		url: API + 'wine/vote' + weight,
		method: 'POST',
		data: {
			idUser: localStorage.getItem('idUser'),
			idWine: addVote ? idWine : null
		}
	});
});

$(document).on('click', '.tasted', function (el) {
	var checked = $(el.target).prop('checked');
	let idWine = $(this).parents('.wine').attr('data-id');

	if (checked) {
		$.ajax({
			url: API + 'user/' + localStorage.getItem('idUser') + '/tasted/' + idWine,
			method: 'POST'
		});
	} else {
		$.ajax({
			url: API + 'user/' + localStorage.getItem('idUser') + '/tasted/' + idWine,
			method: 'DELETE'
		});
	}
});

function changePoints(idWine, delta) {
	let pointSpan = $('.wine[data-id=' + idWine + '] .points');
	let currentPoints = parseInt(pointSpan.text());
	let points = currentPoints + delta;
	pointSpan.text(points);
}

function addWine(wine) {
	$('#wine-list').append(
		'<div class="panel panel-default wine" data-id="' + wine.id + '">' +
		'<div class="panel-heading">' +
		'<h4>#' + wine.id + ' - ' + wine.name + ' - ' + wine.year + ' ' +
		'</h4>' +
		'<input type="checkbox" class="tasted"/>' +
		'</div>' +
		'<div class="panel-body">' +
		'<a href="' + IMG + wine.picture + '" onclick="window.open(this.href);return false;"><img src="' + IMG + wine.picture + '"></a>' +
		'</div>' +
		'<div class="panel-footer">' +
		'<span class="points">' + wine.points + '</span> <span class="glyphicon glyphicon-heart"></span>' +
		'<div class="btn-group pull-right fix-pos" role="group" aria-label="...">' +
		'<button type="button" class="btn btn-default vote" data-weight="3">3 <span class="glyphicon glyphicon-heart"></span></button>' +
		'<button type="button" class="btn btn-default vote" data-weight="2">2 <span class="glyphicon glyphicon-heart"></span></button>' +
		'<button type="button" class="btn btn-default vote" data-weight="1">1 <span class="glyphicon glyphicon-heart"></span></button>' +
		'</div>' +
		'</div>' +
		'</div>');
}

function addResultWine(wine, showUsername) {
	$('#results').append(
		'<div class="panel panel-default wine wine-result" data-id="' + wine.id + '">' +
		'<div class="panel-heading">' +
		'<h4>#' + wine.id + ' - ' + wine.name + ' - ' + wine.year + ' ' +
		'</h4>' +
		'</div>' +
		'<div class="panel-body">' +
		'<a href="' + IMG + wine.picture + '" onclick="window.open(this.href);return false;"><img src="' + IMG + wine.picture + '"></a>' +
		'</div>' +
		'<div class="panel-footer">' +
		'<span class="points">' + wine.points + '</span> <span class="glyphicon glyphicon-heart"></span>' +
		(showUsername ? '<span class="pull-right">' + wine.submitter + '</span>' : '') +
		'</div>' +
		'</div>');
}

function guid() {
	function s4() {
		return Math.floor((1 + Math.random()) * 0x10000)
			.toString(16)
			.substring(1);
	}

	return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
		s4() + '-' + s4() + s4() + s4();
}
