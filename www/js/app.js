(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";

const API = 'http://localhost:3001/v1/';
const IMG = 'http://localhost:3000/upload/';

if ($('#welcome-page').length === 1 && localStorage.getItem('idUser') !== null) {
    window.location = 'voting.html';
    exit;
}

$('#user-form').submit(function () {
    $.ajax({
        url: API + 'user/register',
        method: 'POST',
        data: { 'name': $('#user-name').val() },
        success: function (response) {
            localStorage.setItem('idUser', response.id);
            window.location = 'rules.html';
        }
    });
    return false;
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
                }
            });
        }
    });
}

if ($('#results').length === 1) {
    $.ajax({
        url: API + 'wine/ranking',
        success: function (response) {
            $.each(response, function (i, wine) {
                addResultWine(wine);
            });
        }
    });
}

$(document).on('click', '.vote', function () {
    let addVote = !$(this).hasClass('active');
    $(this).toggleClass('active btn-primary');
    let weight = parseInt($(this).attr('data-weight'));
    let idWine = $(this).parents('.wine').attr('data-id');
    $('.vote[data-weight=' + weight + '].active').not(this).each(function (i, vote) {
        $(vote).removeClass('active btn-primary');
        changePoints($(vote).parents('.wine').attr('data-id'), -weight);
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

function changePoints(idWine, delta) {
    let pointSpan = $('.wine[data-id=' + idWine + '] .points');
    let currentPoints = parseInt(pointSpan.text());
    let points = currentPoints + delta;
    pointSpan.text(points);
}

function addWine(wine) {
    $('#wine-list').append('<div class="panel panel-default wine" data-id="' + wine.id + '">' + '<div class="panel-heading">' + '<h4>#' + wine.id + ' - ' + wine.name + ' - ' + wine.year + ' ' + '</h4>' + '</div>' + '<div class="panel-body">' + '<a href="' + IMG + wine.picture + '" onclick="window.open(this.href);return false;"><img src="' + IMG + wine.picture + '"></a>' + '</div>' + '<div class="panel-footer">' + '<span class="points">' + wine.points + '</span> <span class="glyphicon glyphicon-heart"></span>' + '<div class="btn-group pull-right fix-pos" role="group" aria-label="...">' + '<button type="button" class="btn btn-default vote" data-weight="3">3 <span class="glyphicon glyphicon-heart"></span></button>' + '<button type="button" class="btn btn-default vote" data-weight="2">2 <span class="glyphicon glyphicon-heart"></span></button>' + '<button type="button" class="btn btn-default vote" data-weight="1">1 <span class="glyphicon glyphicon-heart"></span></button>' + '</div>' + '</div>' + '</div>');
}

function addResultWine(wine) {
    $('#results').append('<div class="panel panel-default wine">' + '<div class="panel-heading">' + '<h4>#' + wine.id + ' - ' + wine.name + ' - ' + wine.year + ' ' + '</h4>' + '</div>' + '<div class="panel-body">' + '<a href="' + IMG + wine.picture + '" onclick="window.open(this.href);return false;"><img src="' + IMG + wine.picture + '"></a>' + '</div>' + '<div class="panel-footer">' + '<span class="points">' + wine.points + '</span> <span class="glyphicon glyphicon-heart"></span>' + '</div>' + '</div>');
}

},{}]},{},[1])
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlcy9icm93c2VyLXBhY2svX3ByZWx1ZGUuanMiLCJhcHBcXHNjcmlwdHNcXGFwcC5qcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTtBQ0FBLFlBQVksQ0FBQzs7QUFDYixNQUFNLEdBQUcsR0FBRywyQkFBMkIsQ0FBQztBQUN4QyxNQUFNLEdBQUcsR0FBRywrQkFBK0IsQ0FBQzs7QUFFNUMsSUFBRyxDQUFDLENBQUMsZUFBZSxDQUFDLENBQUMsTUFBTSxLQUFLLENBQUMsSUFBSSxZQUFZLENBQUMsT0FBTyxDQUFDLFFBQVEsQ0FBQyxLQUFLLElBQUksRUFBQztBQUMxRSxVQUFNLENBQUMsUUFBUSxHQUFHLGFBQWEsQ0FBQztBQUNoQyxRQUFJLENBQUM7Q0FDUjs7QUFFRCxDQUFDLENBQUMsWUFBWSxDQUFDLENBQUMsTUFBTSxDQUFDLFlBQVc7QUFDOUIsS0FBQyxDQUFDLElBQUksQ0FBQztBQUNILFdBQUcsRUFBRSxHQUFHLEdBQUcsZUFBZTtBQUMxQixjQUFNLEVBQUUsTUFBTTtBQUNkLFlBQUksRUFBRSxFQUFDLE1BQU0sRUFBRSxDQUFDLENBQUMsWUFBWSxDQUFDLENBQUMsR0FBRyxFQUFFLEVBQUM7QUFDckMsZUFBTyxFQUFFLFVBQVMsUUFBUSxFQUFDO0FBQ3ZCLHdCQUFZLENBQUMsT0FBTyxDQUFDLFFBQVEsRUFBRSxRQUFRLENBQUMsRUFBRSxDQUFDLENBQUM7QUFDNUMsa0JBQU0sQ0FBQyxRQUFRLEdBQUcsWUFBWSxDQUFDO1NBQ2xDO0tBQ0osQ0FBQyxDQUFDO0FBQ0gsV0FBTyxLQUFLLENBQUM7Q0FDaEIsQ0FBQyxDQUFDOztBQUVILElBQUcsQ0FBQyxDQUFDLFlBQVksQ0FBQyxDQUFDLE1BQU0sS0FBSyxDQUFDLEVBQUM7QUFDNUIsS0FBQyxDQUFDLElBQUksQ0FBQztBQUNILFdBQUcsRUFBRSxHQUFHLEdBQUcsTUFBTTtBQUNqQixlQUFPLEVBQUUsVUFBUyxRQUFRLEVBQUM7QUFDdkIsYUFBQyxDQUFDLElBQUks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QVEsQ0FBQyxDQUFDO0NBQ2pCOztBQUVELFNBQVMsYUFBYSxDQUFDLElBQUksRUFBQztBQUN4QixLQUFDLENBQUMsVUFBVSxDQUFDLENBQUMsTUFBTSxDQUNoQix3Q0FBd0MsR0FDcEMsNkJBQTZCLEdBQ3pCLE9BQU8sR0FBRyxJQUFJLENBQUMsRUFBRSxHQUFHLEtBQUssR0FBRyxJQUFJLENBQUMsSUFBSSxHQUFHLEtBQUssR0FBRyxJQUFJLENBQUMsSUFBSSxHQUFHLEdBQUcsR0FDL0QsT0FBTyxHQUNYLFFBQVEsR0FDUiwwQkFBMEIsR0FDdEIsV0FBVyxHQUFHLEdBQUcsR0FBRyxJQUFJLENBQUMsT0FBTyxHQUFHLDZEQUE2RCxHQUFHLEdBQUcsR0FBRyxJQUFJLENBQUMsT0FBTyxHQUFHLFFBQVEsR0FDcEksUUFBUSxHQUNSLDRCQUE0QixHQUN4Qix1QkFBdUIsR0FBRyxJQUFJLENBQUMsTUFBTSxHQUFHLHlEQUF5RCxHQUNyRyxRQUFRLEdBQ1osUUFBUSxDQUFDLENBQUM7Q0FDakIiLCJmaWxlIjoiZ2VuZXJhdGVkLmpzIiwic291cmNlUm9vdCI6IiIsInNvdXJjZXNDb250ZW50IjpbIihmdW5jdGlvbiBlKHQsbixyKXtmdW5jdGlvbiBzKG8sdSl7aWYoIW5bb10pe2lmKCF0W29dKXt2YXIgYT10eXBlb2YgcmVxdWlyZT09XCJmdW5jdGlvblwiJiZyZXF1aXJlO2lmKCF1JiZhKXJldHVybiBhKG8sITApO2lmKGkpcmV0dXJuIGkobywhMCk7dmFyIGY9bmV3IEVycm9yKFwiQ2Fubm90IGZpbmQgbW9kdWxlICdcIitvK1wiJ1wiKTt0aHJvdyBmLmNvZGU9XCJNT0RVTEVfTk9UX0ZPVU5EXCIsZn12YXIgbD1uW29dPXtleHBvcnRzOnt9fTt0W29dWzBdLmNhbGwobC5leHBvcnRzLGZ1bmN0aW9uKGUpe3ZhciBuPXRbb11bMV1bZV07cmV0dXJuIHMobj9uOmUpfSxsLGwuZXhwb3J0cyxlLHQsbixyKX1yZXR1cm4gbltvXS5leHBvcnRzfXZhciBpPXR5cGVvZiByZXF1aXJlPT1cImZ1bmN0aW9uXCImJnJlcXVpcmU7Zm9yKHZhciBvPTA7bzxyLmxlbmd0aDtvKyspcyhyW29dKTtyZXR1cm4gc30pIiwiXCJ1c2Ugc3RyaWN0XCI7XG5jb25zdCBBUEkgPSAnaHR0cDovL2xvY2FsaG9zdDozMDAxL3YxLyc7XHJcbmNvbnN0IElNRyA9ICdodHRwOi8vbG9jYWxob3N0OjMwMDAvdXBsb2FkLyc7XHJcblxyXG5pZigkKCcjd2VsY29tZS1wYWdlJykubGVuZ3RoID09PSAxICYmIGxvY2FsU3RvcmFnZS5nZXRJdGVtKCdpZFVzZXInKSAhPT0gbnVsbCl7XHJcbiAgICB3aW5kb3cubG9jYXRpb24gPSAndm90aW5nLmh0bWwnO1xyXG4gICAgZXhpdDtcclxufVxyXG5cclxuJCgnI3VzZXItZm9ybScpLnN1Ym1pdChmdW5jdGlvbigpIHtcclxuICAgICQuYWpheCh7XHJcbiAgICAgICAgdXJsOiBBUEkgKyAndXNlci9yZWdpc3RlcicsXHJcbiAgICAgICAgbWV0aG9kOiAnUE9TVCcsXHJcbiAgICAgICAgZGF0YTogeyduYW1lJzogJCgnI3VzZXItbmFtZScpLnZhbCgpfSxcclxuICAgICAgICBzdWNjZXNzOiBmdW5jdGlvbihyZXNwb25zZSl7XHJcbiAgICAgICAgICAgIGxvY2FsU3RvcmFnZS5zZXRJdGVtKCdpZFVzZXInLCByZXNwb25zZS5pZCk7XHJcbiAgICAgICAgICAgIHdpbmRvdy5sb2NhdGlvbiA9ICdydWxlcy5odG1sJztcclxuICAgICAgICB9XHJcbiAgICB9KTtcclxuICAgIHJldHVybiBmYWxzZTtcclxufSk7XHJcblxyXG5pZigkKCcjd2luZS1saXN0JykubGVuZ3RoID09PSAxKXtcclxuICAgICQuYWpheCh7XHJcbiAgICAgICAgdXJsOiBBUEkgKyAnd2luZScsXHJcbiAgICAgICAgc3VjY2VzczogZnVuY3Rpb24ocmVzcG9uc2Upe1xyXG4gICAgICAgICAgICAkLmVhY2gocmVzcG9uc2UsIGZ1bmN0aW9uKGksIHdpbmUpIHtcclxuICAgICAgICAgICAgICAgIGFkZFdpbmUod2luZSk7XHJcbiAgICAgICAgICAgIH0pO1xyXG5cclxuICAgICAgICAgICAgJC5hamF4KHtcclxuICAgICAgICAgICAgICAgIHVybDogQVBJICsgJ3VzZXIvJyArIGxvY2FsU3RvcmFnZS5nZXRJdGVtKCdpZFVzZXInKSArICcvdm90ZXMnLFxyXG4gICAgICAgICAgICAgICAgc3VjY2VzczogZnVuY3Rpb24ocmVzcG9uc2Upe1xyXG4gICAgICAgICAgICAgICAgICAgIGlmKHJlc3BvbnNlLnZvdGUxICE9PSBudWxsKXtcclxuICAgICAgICAgICAgICAgICAgICAgICAgJCgnLndpbmVbZGF0YS1pZD0nICsgcmVzcG9uc2Uudm90ZTEgKyAnXSAuYnRuW2RhdGEtd2VpZ2h0PTFdJykuYWRkQ2xhc3MoJ2FjdGl2ZSBidG4tcHJpbWFyeScpO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICBpZihyZXNwb25zZS52b3RlMiAhPT0gbnVsbCl7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICQoJy53aW5lW2RhdGEtaWQ9JyArIHJlc3BvbnNlLnZvdGUyICsgJ10gLmJ0bltkYXRhLXdlaWdodD0yXScpLmFkZENsYXNzKCdhY3RpdmUgYnRuLXByaW1hcnknKTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgaWYocmVzcG9uc2Uudm90ZTMgIT09IG51bGwpe1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAkKCcud2luZVtkYXRhLWlkPScgKyByZXNwb25zZS52b3RlMyArICddIC5idG5bZGF0YS13ZWlnaHQ9M10nKS5hZGRDbGFzcygnYWN0aXZlIGJ0bi1wcmltYXJ5Jyk7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9KVxyXG4gICAgICAgIH1cclxuICAgIH0pO1xyXG59XHJcblxyXG5pZigkKCcjcmVzdWx0cycpLmxlbmd0aCA9PT0gMSl7XHJcbiAgICAkLmFqYXgoe1xyXG4gICAgICAgIHVybDogQVBJICsgJ3dpbmUvcmFua2luZycsXHJcbiAgICAgICAgc3VjY2VzczogZnVuY3Rpb24ocmVzcG9uc2Upe1xyXG4gICAgICAgICAgICAkLmVhY2gocmVzcG9uc2UsIGZ1bmN0aW9uKGksIHdpbmUpe1xyXG4gICAgICAgICAgICAgICAgYWRkUmVzdWx0V2luZSh3aW5lKTtcclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgfVxyXG4gICAgfSlcclxufVxyXG5cclxuJChkb2N1bWVudCkub24oJ2NsaWNrJywgJy52b3RlJywgZnVuY3Rpb24oKXtcclxuICAgIGxldCBhZGRWb3RlID0gISQodGhpcykuaGFzQ2xhc3MoJ2FjdGl2ZScpO1xyXG4gICAgJCh0aGlzKS50b2dnbGVDbGFzcygnYWN0aXZlIGJ0bi1wcmltYXJ5Jyk7XHJcbiAgICBsZXQgd2VpZ2h0ID0gcGFyc2VJbnQoJCh0aGlzKS5hdHRyKCdkYXRhLXdlaWdodCcpKTtcclxuICAgIGxldCBpZFdpbmUgPSAkKHRoaXMpLnBhcmVudHMoJy53aW5lJykuYXR0cignZGF0YS1pZCcpO1xyXG4gICAgJCgnLnZvdGVbZGF0YS13ZWlnaHQ9JyArIHdlaWdodCArICddLmFjdGl2ZScpLm5vdCh0aGlzKS5lYWNoKGZ1bmN0aW9uKGksIHZvdGUpe1xyXG4gICAgICAgICQodm90ZSkucmVtb3ZlQ2xhc3MoJ2FjdGl2ZSBidG4tcHJpbWFyeScpO1xyXG4gICAgICAgIGNoYW5nZVBvaW50cygkKHZvdGUpLnBhcmVudHMoJy53aW5lJykuYXR0cignZGF0YS1pZCcpLCAtd2VpZ2h0KTtcclxuICAgIH0pO1xyXG5cclxuICAgIGNoYW5nZVBvaW50cyhpZFdpbmUsIGFkZFZvdGUgPyB3ZWlnaHQgOiAtd2VpZ2h0KVxyXG5cclxuICAgICQuYWpheCh7XHJcbiAgICAgICAgdXJsOiBBUEkgKyAnd2luZS92b3RlJyArIHdlaWdodCxcclxuICAgICAgICBtZXRob2Q6ICdQT1NUJyxcclxuICAgICAgICBkYXRhOiB7XHJcbiAgICAgICAgICAgIGlkVXNlcjogbG9jYWxTdG9yYWdlLmdldEl0ZW0oJ2lkVXNlcicpLFxyXG4gICAgICAgICAgICBpZFdpbmU6IGFkZFZvdGUgPyBpZFdpbmUgOiBudWxsXHJcbiAgICAgICAgfVxyXG4gICAgfSk7XHJcbn0pO1xyXG5cclxuZnVuY3Rpb24gY2hhbmdlUG9pbnRzKGlkV2luZSwgZGVsdGEpe1xyXG4gICAgbGV0IHBvaW50U3BhbiA9ICQoJy53aW5lW2RhdGEtaWQ9JyArIGlkV2luZSArICddIC5wb2ludHMnKTtcclxuICAgIGxldCBjdXJyZW50UG9pbnRzID0gcGFyc2VJbnQocG9pbnRTcGFuLnRleHQoKSk7XHJcbiAgICBsZXQgcG9pbnRzID0gY3VycmVudFBvaW50cyArIGRlbHRhO1xyXG4gICAgcG9pbnRTcGFuLnRleHQocG9pbnRzKTtcclxufVxyXG5cclxuZnVuY3Rpb24gYWRkV2luZSh3aW5lKXtcclxuICAgICQoJyN3aW5lLWxpc3QnKS5hcHBlbmQoXHJcbiAgICAgICAgJzxkaXYgY2xhc3M9XCJwYW5lbCBwYW5lbC1kZWZhdWx0IHdpbmVcIiBkYXRhLWlkPVwiJyArIHdpbmUuaWQgKyAnXCI+JyArXHJcbiAgICAgICAgICAgICc8ZGl2IGNsYXNzPVwicGFuZWwtaGVhZGluZ1wiPicgK1xyXG4gICAgICAgICAgICAgICAgJzxoND4jJyArIHdpbmUuaWQgKyAnIC0gJyArIHdpbmUubmFtZSArICcgLSAnICsgd2luZS55ZWFyICsgJyAnICtcclxuICAgICAgICAgICAgICAgICc8L2g0PicgK1xyXG4gICAgICAgICAgICAnPC9kaXY+JyArXHJcbiAgICAgICAgICAgICc8ZGl2IGNsYXNzPVwicGFuZWwtYm9keVwiPicgK1xyXG4gICAgICAgICAgICAgICAgJzxhIGhyZWY9XCInICsgSU1HICsgd2luZS5waWN0dXJlICsgJ1wiIG9uY2xpY2s9XCJ3aW5kb3cub3Blbih0aGlzLmhyZWYpO3JldHVybiBmYWxzZTtcIj48aW1nIHNyYz1cIicgKyBJTUcgKyB3aW5lLnBpY3R1cmUgKyAnXCI+PC9hPicgK1xyXG4gICAgICAgICAgICAnPC9kaXY+JyArXHJcbiAgICAgICAgICAgICc8ZGl2IGNsYXNzPVwicGFuZWwtZm9vdGVyXCI+JyArXHJcbiAgICAgICAgICAgICAgICAnPHNwYW4gY2xhc3M9XCJwb2ludHNcIj4nICsgd2luZS5wb2ludHMgKyAnPC9zcGFuPiA8c3BhbiBjbGFzcz1cImdseXBoaWNvbiBnbHlwaGljb24taGVhcnRcIj48L3NwYW4+JyArXHJcbiAgICAgICAgICAgICAgICAnPGRpdiBjbGFzcz1cImJ0bi1ncm91cCBwdWxsLXJpZ2h0IGZpeC1wb3NcIiByb2xlPVwiZ3JvdXBcIiBhcmlhLWxhYmVsPVwiLi4uXCI+JyArXHJcbiAgICAgICAgICAgICAgICAgICAgJzxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwiYnRuIGJ0bi1kZWZhdWx0IHZvdGVcIiBkYXRhLXdlaWdodD1cIjNcIj4zIDxzcGFuIGNsYXNzPVwiZ2x5cGhpY29uIGdseXBoaWNvbi1oZWFydFwiPjwvc3Bhbj48L2J1dHRvbj4nICtcclxuICAgICAgICAgICAgICAgICAgICAnPGJ1dHRvbiB0eXBlPVwiYnV0dG9uXCIgY2xhc3M9XCJidG4gYnRuLWRlZmF1bHQgdm90ZVwiIGRhdGEtd2VpZ2h0PVwiMlwiPjIgPHNwYW4gY2xhc3M9XCJnbHlwaGljb24gZ2x5cGhpY29uLWhlYXJ0XCI+PC9zcGFuPjwvYnV0dG9uPicgK1xyXG4gICAgICAgICAgICAgICAgICAgICc8YnV0dG9uIHR5cGU9XCJidXR0b25cIiBjbGFzcz1cImJ0biBidG4tZGVmYXVsdCB2b3RlXCIgZGF0YS13ZWlnaHQ9XCIxXCI+MSA8c3BhbiBjbGFzcz1cImdseXBoaWNvbiBnbHlwaGljb24taGVhcnRcIj48L3NwYW4+PC9idXR0b24+JyArXHJcbiAgICAgICAgICAgICAgICAnPC9kaXY+JyArXHJcbiAgICAgICAgICAgICc8L2Rpdj4nICtcclxuICAgICAgICAnPC9kaXY+Jyk7XHJcbn1cclxuXHJcbmZ1bmN0aW9uIGFkZFJlc3VsdFdpbmUod2luZSl7XHJcbiAgICAkKCcjcmVzdWx0cycpLmFwcGVuZChcclxuICAgICAgICAnPGRpdiBjbGFzcz1cInBhbmVsIHBhbmVsLWRlZmF1bHQgd2luZVwiPicgK1xyXG4gICAgICAgICAgICAnPGRpdiBjbGFzcz1cInBhbmVsLWhlYWRpbmdcIj4nICtcclxuICAgICAgICAgICAgICAgICc8aDQ+IycgKyB3aW5lLmlkICsgJyAtICcgKyB3aW5lLm5hbWUgKyAnIC0gJyArIHdpbmUueWVhciArICcgJyArXHJcbiAgICAgICAgICAgICAgICAnPC9oND4nICtcclxuICAgICAgICAgICAgJzwvZGl2PicgK1xyXG4gICAgICAgICAgICAnPGRpdiBjbGFzcz1cInBhbmVsLWJvZHlcIj4nICtcclxuICAgICAgICAgICAgICAgICc8YSBocmVmPVwiJyArIElNRyArIHdpbmUucGljdHVyZSArICdcIiBvbmNsaWNrPVwid2luZG93Lm9wZW4odGhpcy5ocmVmKTtyZXR1cm4gZmFsc2U7XCI+PGltZyBzcmM9XCInICsgSU1HICsgd2luZS5waWN0dXJlICsgJ1wiPjwvYT4nICtcclxuICAgICAgICAgICAgJzwvZGl2PicgK1xyXG4gICAgICAgICAgICAnPGRpdiBjbGFzcz1cInBhbmVsLWZvb3RlclwiPicgK1xyXG4gICAgICAgICAgICAgICAgJzxzcGFuIGNsYXNzPVwicG9pbnRzXCI+JyArIHdpbmUucG9pbnRzICsgJzwvc3Bhbj4gPHNwYW4gY2xhc3M9XCJnbHlwaGljb24gZ2x5cGhpY29uLWhlYXJ0XCI+PC9zcGFuPicgK1xyXG4gICAgICAgICAgICAnPC9kaXY+JyArXHJcbiAgICAgICAgJzwvZGl2PicpO1xyXG59XHJcbiJdfQ==
