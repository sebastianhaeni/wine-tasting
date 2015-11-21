const config = require('./config');
var showUsername = false;

if($('#results').length === 1){
    $.ajax({
        url: config.API + 'config/show-usernames',
        success: function(response){
            showUsername = response === 'true';
            $.ajax({
                url: config.API + 'wine/ranking',
                success: function(response){
                    $.each(response, function(i, wine){
                        addResultWine(wine, showUsername);
                    });
                }
            });
        }
    });

    createListStyles(".wine-result:nth-child({0})", 50, $(window).width() / 420);

    setTimeout(updateResults, 4000);
}

function updateResults(){
    $.ajax({
        url: config.API + 'wine/ranking',
        success: function(response){
            let lastWine = null;
            $.each(response, function(i, wine){
                if($('.wine[data-id=' + wine.id + ']').length !== 1){
                    addResultWine(wine, showUsername);
                }
                $('.wine[data-id=' + wine.id + ']').attr('data-points', wine.points);
                $('.wine[data-id=' + wine.id + '] .points').text(wine.points);
            });
            $('.wine').snapshotStyles();
            tinysort('.wine', {attr: 'data-points', order: 'desc'});
            $('.wine').releaseSnapshot();
        }
    });

    //setTimeout(updateResults, 180000); // 3 minutes
    setTimeout(updateResults, 5000); // 3 minutes
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
            let x = (10+(colIndex * 110)) + "%",
                y = (10+(rowIndex * 110)) + "%",
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

function addResultWine(wine, showUsername){
    let id = showUsername ? '#' + wine.id + ' - ' : '';
    $('#results').append(
        '<div class="panel panel-default wine wine-result" data-id="' + wine.id + '">' +
            '<div class="panel-heading">' +
                '<h4>' + id + wine.name + ' - ' + wine.year + ' ' +
                '</h4>' +
            '</div>' +
            '<div class="panel-body">' +
                '<a href="' + config.IMAGE_PATH + wine.picture + '" onclick="window.open(this.href);return false;"><img src="' + config.IMAGE_PATH + wine.picture + '"></a>' +
            '</div>' +
            '<div class="panel-footer">' +
                '<span class="points">' + wine.points + '</span> <span class="glyphicon glyphicon-heart"></span>' +
                '<span class="pull-right">' + wine.submitter + '</span>' +
            '</div>' +
        '</div>');
}
