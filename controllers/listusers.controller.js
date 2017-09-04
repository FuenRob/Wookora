$(function() {
    var session = Cookies.get('session_us');
    if(!session){
        window.location.href = "login.html"
    }else{
        $('#navLogout').hide();
        $('#navLogin').show();
    }
    
    $('#logout').on('click', function (e) {
        Cookies.remove('session_us');
        location.reload();
    });
    var action = "userlist";
    $.ajax({
        type: 'POST',
        url: 'http://localhost/framework/core/',
        dataType: "json",
        data: 'action=' + action,
        success: function (data) {
            $.each(data, function(index) {
                $('#tbody').append("<tr><td>"+data[index].name+"</td><td>"+data[index].email+"</td><td>"+data[index].dateCreation+"</td></tr>")
            });
        },
        error: function (data) {
            console.log('Error: ');
            console.log(data);
        }
    });
    $('#logout').on('click', function (e) {
        Cookies.remove('session_us');
        location.reload();
    });
});

