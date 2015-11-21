const config = require('./config');

if($('#wine-list').length === 1){
    $.ajax({
        url: config.API + 'config/show-usernames',
        success: function(response){
            let showUsername = response === 'true';
            $.ajax({
                url: config.API + 'wine',
                success: function(response){
                    $.each(response, function(i, wine) {
                        addWine(wine, showUsername);
                    });

                    $.ajax({
                        url: config.API + 'user/' + localStorage.getItem('idUser') + '/votes',
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
                        },
                        error: function(){
                            localStorage.removeItem('idUser');
                            window.location = '/';
                        }
                    })
                }
            });
        }
    });
}

function addWine(wine, showUsername){
    let title = showUsername ? ' - ' + wine.name + ' - ' + wine.year : '';
    let image = showUsername ? config.IMAGE_PATH + wine.picture : 'images/bottle.jpg';
    let points = showUsername ? '<span class="points">' + wine.points + '</span> <span class="glyphicon glyphicon-heart"></span>' : '<span>&nbsp;</span>';

    $('#wine-list').append(
        '<div class="panel panel-default wine" data-id="' + wine.id + '">' +
            '<div class="panel-heading">' +
                '<h4>#' + wine.id + title + '</h4>' +
            '</div>' +
            '<div class="panel-body">' +
                '<a href="' + image + '" onclick="window.open(this.href);return false;"><img src="' + image + '"></a>' +
            '</div>' +
            '<div class="panel-footer">' +
                points +
                '<div class="btn-group pull-right fix-pos" role="group" aria-label="...">' +
                    '<button type="button" class="btn btn-default vote" data-weight="3">3 <span class="glyphicon glyphicon-heart"></span></button>' +
                    '<button type="button" class="btn btn-default vote" data-weight="2">2 <span class="glyphicon glyphicon-heart"></span></button>' +
                    '<button type="button" class="btn btn-default vote" data-weight="1">1 <span class="glyphicon glyphicon-heart"></span></button>' +
                '</div>' +
            '</div>' +
        '</div>');
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

    $('.wine[data-id=' + idWine + '] .active').not(this).each(function(i, vote){
        $(vote).removeClass('active btn-primary');
        let thisWeight = parseInt($(this).attr('data-weight'));
        changePoints($(vote).parents('.wine').attr('data-id'), -thisWeight);

        $.ajax({
            url: config.API + 'wine/vote' + thisWeight,
            method: 'POST',
            data: {
                idUser: localStorage.getItem('idUser'),
                idWine: null
            }
        });
    });

    changePoints(idWine, addVote ? weight : -weight)

    $.ajax({
        url: config.API + 'wine/vote' + weight,
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
