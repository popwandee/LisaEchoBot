var CLOUDINARY_URL ='https://api.cloudinary.com/v1_1/dly6ftryr/upload';
var CLOUDINARY_UPLOAD_PRESET = 'hg7cuaaf';

var imgPreview = document.getElementById('img-preview');
var fileUpload = document.getElementById('file-upload');

fileUpload.addEventListener('change', function(event){
  var file = event.target.files[0];
  var formData = new FormData();
  formData.append('file',file);
  formData.append('upload_preset',CLOUDINARY_UPLOAD_PRESET);

  axios({
    url: CLOUDINARY_URL,
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    data: FormData
  }).then(function(res) {
    console.log(res);
    imgPreview.src= rec.data.secure_url;
  }).catch(function(err) {
    console.error(err);
  });
});
