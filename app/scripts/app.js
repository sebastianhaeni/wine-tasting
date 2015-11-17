const API = 'http://localhost:3001/v1/';
const IMG = 'http://localhost:3000/upload/';

if($('#welcome-page').length === 1 && localStorage.getItem('idUser') !== null){
    window.location = 'voting.html';
    exit;
}

$('#user-form').submit(function() {
    $.ajax({
        url: API + 'user/register',
        method: 'POST',
        data: {'name': $('#user-name').val()},
        success: function(response){
            localStorage.setItem('idUser', response.id);
            window.location = 'rules.html';
        }
    });
    return false;
});

if($('#wine-list').length === 1){
    $.ajax({
        url: API + 'wine',
        success: function(response){
            $.each(response, function(i, wine) {
                addWine(wine);
            });

            $.ajax({
                url: API + 'user/' + localStorage.getItem('idUser') + '/votes',
                success: function(response){
                    if(response.vote1 !== null){
                        $('.wine[data-id=' + response.vote1 + '] .btn[data-weight=1]').addClass('active btn-primary');
                    }
                    if(response.vote2 !== null){
                        $('.wine[data-id=' + response.vote2 + '] .btn[data-weight=2]').addClass('active btn-primary');
                    }
                    if(response.vote3 !== null){
                        $('.wine[data-id=' + response.vote3 + '] .btn[data-weight=3]').addClass('active btn-primary');
                    }
                }
            })
        }
    });
}

if($('#results').length === 1){
    $.ajax({
        url: API + 'wine/ranking',
        success: function(response){
            $.each(response, function(i, wine){
                addResultWine(wine);
            });
        }
    })
}

$(document).on('click', '.vote', function(){
    let addVote = !$(this).hasClass('active');
    $(this).toggleClass('active btn-primary');
    let weight = parseInt($(this).attr('data-weight'));
    let idWine = $(this).parents('.wine').attr('data-id');
    $('.vote[data-weight=' + weight + '].active').not(this).each(function(i, vote){
        $(vote).removeClass('active btn-primary');
        changePoints($(vote).parents('.wine').attr('data-id'), -weight);
    });

    changePoints(idWine, addVote ? weight : -weight)

    $.ajax({
        url: API + 'wine/vote' + weight,
        method: 'POST',
        data: {
            idUser: localStorage.getItem('idUser'),
            idWine: addVote ? idWine : null
        }
    });
});

function changePoints(idWine, delta){
    let pointSpan = $('.wine[data-id=' + idWine + '] .points');
    let currentPoints = parseInt(pointSpan.text());
    let points = currentPoints + delta;
    pointSpan.text(points);
}

function addWine(wine){
    $('#wine-list').append(
        '<div class="panel panel-default wine" data-id="' + wine.id + '">' +
            '<div class="panel-heading">' +
                '<h4>#' + wine.id + ' - ' + wine.name + ' - ' + wine.year + ' ' +
                '</h4>' +
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

function addResultWine(wine){
    $('#results').append(
        '<div class="panel panel-default wine">' +
            '<div class="panel-heading">' +
                '<h4>#' + wine.id + ' - ' + wine.name + ' - ' + wine.year + ' ' +
                '</h4>' +
            '</div>' +
            '<div class="panel-body">' +
                '<a href="' + IMG + wine.picture + '" onclick="window.open(this.href);return false;"><img src="' + IMG + wine.picture + '"></a>' +
            '</div>' +
            '<div class="panel-footer">' +
                '<span class="points">' + wine.points + '</span> <span class="glyphicon glyphicon-heart"></span>' +
            '</div>' +
        '</div>');
}
