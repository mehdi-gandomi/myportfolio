var base_url=window.location.origin+"/coderguy";
var editForm=$("#editProfile");
editForm.on("submit",function (e) {
    e.preventDefault();
    var formData=new FormData($(this)[0]);
    $.ajax({
        url: base_url+"/dashboard/edit-profile-post",
        type: 'POST',
        data: formData,
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,
        // mimeType: "multipart/form-data",
        beforeSend: function (e) {

        },
        success: function (data) {
            console.log(data);
            data = JSON.parse(data);
            if (data.status=='ok'){
                iziToast.success({
                    message: 'اطلاعات با موفقیت ثبت شد!',
                });
            }else {
                iziToast.error({

                    message: 'در ثبت اطلاعات خطایی رخ داد',
                });
            }

        }
    });

});