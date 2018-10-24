var base_url=document.querySelector("base").getAttribute("href");
$("#new-folder-btn").on("click",function(e){
    $(".newFolderForm").toggleClass("show");
});
function showPostsPagination(paginationData, el) {
    console.log(paginationData);
    let output="";

    if (!paginationData.firstPage){
        output+=`
                    <li class="page-item">
                        <a class="page-link" href="${paginationData.prevUrl}">
                            &laquo;
                        </a>
                    </li>
            `;

    }
    if (paginationData['pages'] ){
        let pages=paginationData['pages'];
        Object.keys(pages).map(function(key, index){
            if(pages[key].active){
                output+=`
                    <li class="page-item active">
                        <a class="page-link" href="${pages[key].url}">${pages[key].title}</a>
                    </li>
                `;
            }else {
                output+=`
                    <li class="page-item">
                        <a class="page-link" href="${pages[key].url}">${pages[key].title}</a>
                    </li>
                `;
            }
        });


    }
    if (!paginationData.lastPage){
        console.log(paginationData.is_last_page);
        output+=`
                    <li class="page-item">
                        <a class="page-link" href="${paginationData.nextUrl}">
                            &raquo;
                        </a>
                    </li>
            `;
    }
    el.html(output);
}
function showPosts(data,el) {
    var output="";
    for (post of data){
        output+="<tr>";
        if(post.post_thumbnail){
            output+=`<td><img class="img-circle" src='${post.post_thumbnail}' alt=''></td>`;
        }else {
            output+=`<td><img class="img-circle" src=http://localhost/picfaker/?width=150&height=150' alt=''></td>`;
        }
        output+=`<td>${post.post_title}</td>`;
        output+=`<td>${post.post_slug}</td>`;
        output+=`<td style="width: 30%;">${post.preview_text}</td>`;
        output+=`<td>${post.tags}</td>`;
        output+=`
                <td>
                     <a href="dashboard/posts/edit/${post.post_id}" class="btn btn-primary" )">ویرایش</a>
                     <button class="btn btn-danger" onclick="deletePost('${post.post_id}')">
                                                حذف
                     </button>
                </td>
            `;

        output+="</tr>";
    }
    el.html(output);
}
function getAllPosts() {
    let urlToGo="";
    let urlArr=window.location.pathname.split("/");
    if (urlArr[4]){
        urlToGo=base_url+"dashboard/"+urlArr[3]+"/"+urlArr[4]+"/json";
    }else {
        urlToGo=base_url+"dashboard/"+urlArr[3]+"/json";
    }
    $.get(urlToGo,function (data) {
        data=JSON.parse(data);
        showPosts(data.posts,$("#posts-wrapper"));
        showPostsPagination(data.pagination,$("#posts-pagination"));
    })
}
function deletePost(post_id) {
    if (confirm("واقعا می خوای این پست رو پاک کنی؟")){
        $.ajax({
            url: base_url+"dashboard/posts"+"/"+post_id,
            type: 'DELETE',
            beforeSend: function (e) {

            },
            success: function (data) {
                console.log(data);
                data = JSON.parse(data);
                if (data.status=='ok'){
                    iziToast.success({
                        message: 'پست با موفقیت حذف شد!',
                    });
                    getAllPosts();
                }else {
                    iziToast.error({

                        message: 'در ثبت اطلاعات خطایی رخ داد',
                    });
                }

            }
        });
    }else {
        iziToast.warning({

            message: 'در ثبت اطلاعات خطایی رخ داد',
        });
    }
}
function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}
var suneditor = SUNEDITOR.create('editor',{
    imageUploadUrl:base_url+"/dashboard/editor/uploadImage.ajax"
});
$('.selectpicker').selectpicker({
    noneSelectedText:"هیچ چیزی انتخاب نشده است"
});
$("#newPostForm").on("submit",function (e) {
    e.preventDefault();
//        $("#post_content").html(suneditor.getContent());
//        let postTextContent=suneditor.getContent();
//        let previewText="";
//        postTextContent=postTextContent.replace(/\w+<\/[^>]+>/g,"<br>");
//        postTextContent = postTextContent.replace(/<(?!\/?br(?=>|\s.*>))\/?.*?>/g,"");
//        if (postTextContent.length>200){
//            previewText=postTextContent.substr(0,200);
//        }else {
//            previewText=postTextContent;
//        }
//        console.log(previewText);
//        $(this).find("#preview_text").html(previewText);
    suneditor.save();
    var formData=new FormData($(this)[0]);

    $.ajax({
        url: $(this).attr("action"),
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
                getAllPosts();
            }else {
                iziToast.error({

                    message: 'در ثبت اطلاعات خطایی رخ داد',
                });
            }

        }
    });
});

var clearBtn=document.querySelector(".custom-file-container__image-clear");

function listAllDirectories(route) {
    if(route===undefined){
        route="public/images/uploads";
    }
    $.get(base_url+"dashboard/file-manager",{"route":route},function (data) {
        $("#file-management-form").html(data);
    });    
    
}
$("#file-manager").on("show.bs.modal",function (e) {
    listAllDirectories();
});

$(document).on("click",".folder a",function (e) {
   e.preventDefault();
   let folderPath=e.currentTarget.getAttribute("href");
  listAllDirectories(folderPath);
});
$("#button-parent").click(function (e) {
    e.preventDefault();
    let curPath=$("#current-path").val();
    curPath=curPath.substring(0,curPath.lastIndexOf("/"));
    listAllDirectories(curPath);
});
$("#button-refresh").click(function (e) {
    e.preventDefault();
    let curPath=$("#current-path").val();
    $.get(base_url+"dashboard/file-manager",{"route":curPath},function (data) {
        $("#file-management-form").html(data);
    })
});
$("#folder-create-btn").click(function (e) {
    let folderName=$("input[name='folder']").val();
    let curPath=$("#current-path").val();
    $.ajax({
        url: base_url+"dashboard/file-manager",
        type: 'POST',
        data:{
            'folder':folderName,
            'current_path':curPath
        },
        beforeSend: function (e) {

        },
        success: function (data) {
            console.log(data);
            data = JSON.parse(data);
            if (data.status=='ok'){
                iziToast.success({
                    message: 'پوشه با موفقیت ایجاد شد!',
                });
                $(".newFolderForm").removeClass("show");
                listAllDirectories(curPath);

            }else {
                iziToast.error({

                    message: 'در ایجاد پوشه خطایی رخ داد!',
                });
            }

        }
    });
});
// deleteBtn=document.querySelector("#button-delete");
$("#button-delete").on("click",function(){
    if(confirm("واقعا می خوای موارد انتخابی رو پاک کنی؟")){
        let curPath=$("#current-path").val();
        $.ajax({
            url: base_url+"dashboard/file-manager",
            type: 'DELETE',
            data:$("#file-management-form").serialize(),
            beforeSend: function (e) {

            },
            success: function (data) {
                console.log(data);
                data = JSON.parse(data);
                if (data.status=='ok'){
                    iziToast.success({
                        message: 'موارد انتخابی با موفقیت حذف شدند!',
                    });
                    listAllDirectories(curPath);
                }else {
                    iziToast.error({

                        message: ' خطایی رخ داد',
                    });
                }

            }
        });     
    }else{

    }
});

$("#img-upload").on("change",function(e){
    let files = e.target.files;
    
    var data = new FormData();
    $.each(files, function(key, value)
    {
        data.append(key, value);
    });
    let curPath=$("#current-path").val();
    data.append("current_path",curPath);

    $.ajax({
        url: base_url+"dashboard/file-manager",
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success: function(data, textStatus, jqXHR)
        {
            console.log(data);
            if (data.status=='ok'){
                iziToast.success({
                    message: 'اطلاعات با موفقیت ثبت شد!',
                });
                listAllDirectories(curPath);
            }else {
                iziToast.error({

                    message: 'در ثبت اطلاعات خطایی رخ داد',
                });
            }

        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            // Handle errors here
            console.log('ERRORS: ' + textStatus);
            // STOP LOADING SPINNER
        }
    });

})
    
$(document).on("click",".file",function(e){
    e.preventDefault();
    thumbnail=e.currentTarget.querySelector("a").getAttribute("href");
    $("#thumbnail_url").val(thumbnail);
    $(".thumbnail-preview").css("background-image","url('"+thumbnail+"')");
    $("#file-manager").modal("hide");
});