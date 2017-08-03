$(function() {
    $('#title').html("Hello!");
    $('#body').html("My name is Roberto Morais and it is my Framework.");
    var session = Cookies.get('session_us');
    if(!session){
        $('#navLogout').show();
        $('#navLogin').hide();
    }else{
        $('#navLogout').hide();
        $('#navLogin').show();
    }
    $('#logout').on('click', function (e) {
        Cookies.remove('session_us');
        location.reload();
    });
});


