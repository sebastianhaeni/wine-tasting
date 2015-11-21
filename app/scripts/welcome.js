const config = require('./config');

function guid() {
    function s4() {
        return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
    }
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
        s4() + '-' + s4() + s4() + s4();
}

if($('#welcome-page').length === 1 && localStorage.getItem('idUser') !== null){
    window.location = 'voting.html';
    exit;
}

$('#user-form').submit(function() {
    let idUser = guid();
    $.ajax({
        url: config.API + 'user/register',
        method: 'POST',
        headers: { "cache-control": "no-cache" },
        data: {'name': idUser},
        success: function(response){
            localStorage.setItem('idUser', response.id);
            window.location = 'rules.html';
        }
    });
    return false;
});
