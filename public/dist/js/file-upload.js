var imgUpload = document.getElementById('upload_imgs')
  , imgPreview = document.getElementById('img_preview')
  , imgUploadForm = document.getElementById('img-upload-form')
  , totalFiles
  , previewTitle
  , previewTitleText
  , img;

imgUpload.addEventListener('change', previewImgs, false);


function previewImgs(event) {
  totalFiles = imgUpload.files.length;
  imgPreview.innerHTML="";
  if(!!totalFiles) {
    previewTitle = document.createElement('p');
    previewTitle.style.fontWeight = 'bold';
    previewTitleText = document.createTextNode(totalFiles + ' تصویر انتخاب شده');
    previewTitle.appendChild(previewTitleText);
    imgPreview.appendChild(previewTitle);
  }
  
  for(var i = 0; i < totalFiles; i++) {
    img = document.createElement('img');
    img.src = URL.createObjectURL(event.target.files[i]);
    img.classList.add('img-preview-thumb');
    imgPreview.appendChild(img);
  }
}