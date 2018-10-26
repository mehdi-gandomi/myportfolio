function $$$(el){
    return document.querySelector(el);
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            let imgPreview=$$$('#imagePreview');
            imgPreview.style.backgroundImage = 'url('+e.target.result +')';
            // imgPreview.style.display="none";
            imgPreview.classList.add("fade-in-fwd");
            
            // $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$$$("#imageUpload").addEventListener("change",function(e) {
    let imgPreview=$$$('#imagePreview');
    
    if(imgPreview.classList.contains("fade-in-fwd")){
        imgPreview.classList.remove("fade-in-fwd");
    }
    readURL(e.currentTarget);
});