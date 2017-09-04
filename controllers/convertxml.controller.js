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
    
    $("#form").on('submit', function (e) {
        action = $("#submit").attr("data-action");
        e.preventDefault();
        //grab all form data  
        var formData = new FormData($(this)[0]);
        formData.append('action', action);
        $.ajax({
          url: 'http://localhost/framework/core/',
          type: 'POST',
          data: formData,
          async: false,
          cache: false,
          contentType: false,
          processData: false,
          success: function (data) {
            console.log(data);
            if(data[0] == 1){
                $('#modal').modal('show');
                $('#titleStatus').html(data[1]);
                $('#status').html(data[2]);
            }else{
                $('#modal').modal('show');
                $('#titleStatus').html(data[1]);
                $('#status').html(data[2]);
            }
          }
        });
    });
});