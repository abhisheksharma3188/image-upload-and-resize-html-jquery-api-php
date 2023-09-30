<?php
header('Content-Type:application/json');
header('Access-Control-Allow-Origin:*');

$jwt=getallheaders()['Authorization'];

$text_input=$_POST['text_input'];


//////////////////////////////////// code to check if all input fields are empty below ////////////////
if(str_replace(' ','',$text_input)=='' && $_FILES['file_input']['size']=='0'){
    $response_array=['response'=>'failure','message'=>'All fields cannot be empty.'];
    $response_json=json_encode($response_array);
    echo $response_json;
    die;
}
//////////////////////////////////// code to check if all input fields are empty above ////////////////

////////////// code to check image file type below //////////////////////////////////////
if($_FILES['file_input']['type']!='image/jpeg' && $_FILES['file_input']['type']!='image/png'){
    $response_array=['response'=>'failure','message'=>'Only JPG and PNG format are allowed.'];
    $response_json=json_encode($response_array);
    echo $response_json;
    die;
}    
//////////// code to check image file type above ///////////////////////////////////////

//////////// code to get image file type below //////////////////////////////////
if($_FILES['file_input']['type']=='image/jpeg'){
    $image_file_type='jpg';
}    
if($_FILES['file_input']['type']=='image/png'){
    $image_file_type='png';
}    
//////////// code to get image file type above //////////////////////////////////

//////////////////////// Code to get new filename for image below ////////////////
if($_FILES['file_input']['size']!='0'){
    $image_new_file_name= uniqid();
}
//////////////////////// Code to get new filename for image above ////////////////

//////////// code to set new name of image below ///////////////////////////////
if($_FILES['file_input']['size']!='0'){
    $image_new_name=$image_new_file_name.'.'.$image_file_type;
}
else{
    $image_new_name='';    
}
//////////// code to set new name of image above ///////////////////////////////

//////////// code to upload image below //////////////////////////////////////////
if($_FILES['file_input']['size']!='0'){
    $file=$_FILES['file_input']['tmp_name'];
    list($width,$height)=getimagesize($file);
    $nwidth=300; ////////////////// write new width in pixels here///////////////
    $nheight=300;////////////////// write new height in pixels here///////////////
    $newimage=imagecreatetruecolor($nwidth,$nheight);
    imagealphablending($newimage, false);
    imagesavealpha($newimage, true);
    if($_FILES['file_input']['type']=='image/jpeg'){
        $source=imagecreatefromjpeg($file);
        imagecopyresized($newimage,$source,0,0,0,0,$nwidth,$nheight,$width,$height);
        imagejpeg($newimage,'../image_uploads/'.$image_new_name);/////// code to upload image ///////
    }
    if($_FILES['file_input']['type']=='image/png'){
        $source=imagecreatefrompng($file);
        imagecopyresized($newimage,$source,0,0,0,0,$nwidth,$nheight,$width,$height);
        imagepng($newimage,'../image_uploads/'.$image_new_name);/////// code to upload image ///////
    }
}

////////////// code to upload image above//////////////////////////////////////

////////////// code to send response below ////////////////////////////////////
$response_array=['response'=>'success','message'=>'Image uploaded successfully.','text_input'=>$text_input,'jwt'=>$jwt,'image_new_name'=>$image_new_name];
$response_json=json_encode($response_array);
echo $response_json;
die;
////////////// code to send response above ////////////////////////////////////

?>
