$(document).ready(function (e){
    $('#ImgProfile').change(function () {
        let reader =new FileReader();
        reader.onload =(e) => {
            $('#oldImg').attr('src',e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
    });
});

function changeProfile(id) {
    var fileInput = document.getElementById('pic'+id);
    var imageElement = document.getElementById('imgOld'+id)
    if(fileInput.files && fileInput.files[0]){
        var reader = new FileReader();
        reader.onload= function(e){
            imageElement.src = e.target.result;
        };
        reader.readAsDataURL(fileInput.files[0]);
    }
  }
