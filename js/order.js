//
// 注文フォームJS
//

$(function(){
    $('#meishi_data').hide();

    // 入稿データアップ
    $('#meishi_data').on('change', function(evn) {
        var form = $('#form').get()[0];
        var formData = new FormData(form);
        $.ajax({
            url: "upload.php",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json'
        })
        .done(function(status) {
            $('#result').text(status.msg);
            if (status.code == '0') {
                $('#filename').text(status.filename);
            }
        });

        return false;
    });
});