$(function() {
    $("#form").on('submit', function (e) {
        action = $("#submit").attr("data-action");
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'http://localhost/framework/core/',
            dataType: "json",
            data: 'action=' + action + '&' + $(this).serialize(),
            success: function (data) {
                if(data[0] == 1){
                    Cookies.set('session_us', data[1], { expires: 1 });
                    window.location.href = "index.html";
                }else{
                    $('#modal').modal('show');
                    $('#titleStatus').html(data[1]);
                    $('#status').html(data[2]);
                }
            },
            error: function (data) {
                console.log(data);
                alert('Error in web service.');
            }
        });
    });
});


