<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head> 
    <body>
        <div style="width:500px;margin:0px auto 0px auto">
            
            <!--------------------------------- Actual image from server div below --------------------------------------------->
            <div style="text-align:center;margin-bottom:50px;">
                <img id="actual_img" src="" style="width:200px;height:200px;" alt="Actual Image From Server">
            </div>
            <!--------------------------------- Actual image from server div above --------------------------------------------->
            
            <!--------------------------------- Preview of image to upload div below ------------------------------------------->
            <div style="text-align:center;margin-bottom:50px;">
                <img id="preview_img" src="" style="width:100px;height:100px;" alt="Preview Of Image Being Uploaded">
            </div>
            <!--------------------------------- Preview of image to upload div above ------------------------------------------->
            
            <!--------------------------------- Upload image form below -------------------------------------------------------->
            <form id="image_upload_form" action="api.php" method="post" enctype="multipart/form-data">
                <input type="text" name="text_input" placeholder="Any Text"><br><br>
                <input id="file_input" type="file" name="file_input"><br><br>
                <progress id="image_upload_progress" value="0" max="100"> </progress><br><br>
                <input type="submit" value="Upload Image" name="submit"><br><br>
                <div id="image_upload_form_response_div"></div>
            </form>
            <!--------------------------------- Upload image form above -------------------------------------------------------->
            
        </div>
        <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
        <script>
            
            /////////////////////////////////// Code to show image preview below ////////////////////////////////////////////////
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
            
                    reader.onload = function (e) {
                        $('#preview_img').attr('src', e.target.result);
                    }
            
                    reader.readAsDataURL(input.files[0]);
                }
            }
            
            $("#file_input").change(function(){
                readURL(this);
            });
            /////////////////////////////////// Code to show image preview above ////////////////////////////////////////////////
            
            /////////////////////////////////// Image upload form ajax submission code below ////////////////////////////////////
            $('#image_upload_form').on('submit', function() {
                $("#image_upload_form_response_div").html('');
                var formData = new FormData(this);
                var headers_obj = {"Authorization":"Bearer <?php echo '1234567890' /*@$_COOKIE['jwt_token_website'];*/ ?>"};
                $.ajax({
                    url: "api/api.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers:headers_obj,
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = (evt.loaded / evt.total) * 100;
                                document.getElementById('image_upload_progress').value=percentComplete;
                            }
                        }, false);
                    return xhr;
                    },
                    success: function(data) {
                        console.log(data);
                        if (data.response == 'success') {
                            $("#image_upload_form_response_div").css({"color":"green"});
                            $("#image_upload_form_response_div").html(data.message);
                            document.getElementById('actual_img').src=`image_uploads/${data.image_new_name}`;
                        }
                        if (data.response == 'failure') {
                            $("#image_upload_form_response_div").css({"color":"red"});
                            $("#image_upload_form_response_div").html(data.message);
                        }
                        document.getElementById('image_upload_progress').value=0;
                    },
                    error: function() {
                        alert('Some Error Occured.');
                    }
                });
                return false;
            });
            /////////////////////////////////// Image upload form ajax submission code below ////////////////////////////////////
        </script>
    </body>
</html>