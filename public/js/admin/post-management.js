var base_url=document.querySelector("base").getAttribute("href");
var postTags=[];
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
    formData.append("tags",postTags.join(","));
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


// tags listener

function createEl(el,value){
    let element=document.createElement(el)
    element.innerHTML=value;
    return element;
}
function clearList(){
    let tags=document.querySelector(".tags");
    if (tags.textContent.trim()=="هیچ برچسبی انتخاب نشده است"){
        tags.innerHTML="";
    }

}
let tag=document.querySelector("#tag");
let tags=document.querySelector(".tags");

function addListener(el,type,callback,multiple){
    if(multiple===undefined){
        multiple=false;
    }
    if(multiple){
        elements=document.querySelectorAll(el);
        for(let i=0;i<elements.length;i++){
            elements[i].addEventListener(type,callback);
        }
    }else{
        element=document.querySelector(el);
        element.addEventListener(type,callback(e));
    }
}
tag.addEventListener("keydown",function(e){
    
    if(e.keyCode===13){
        e.preventDefault();
        postTags.push(e.target.value);
        clearList();
        let li=createEl("li",e.target.value);
        tags.appendChild(li);
        addListener(".tags li","click",function(e){
            e.target.parentNode.removeChild(e.target);
            postTags=postTags.filter(function(tag){
                return tag!=e.target.textContent;
            });
        },true);
        
    }else{
        return false;
    }
});

