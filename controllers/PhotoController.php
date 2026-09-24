<?php
class PhotoController
{
    public function index(): void { $photos=(new Photo())->all($_GET['sort']??'latest'); render('photos/index',['title'=>'Gallery','photos'=>$photos,'style'=>$_GET['style']??'grid']); }
    public function show(int $id): void { $photo=(new Photo())->find($id); if (!$photo) { http_response_code(404); render('photos/not-found',['title'=>'Not found']); return; } render('photos/show',['title'=>$photo['title'],'photo'=>$photo,'comments'=>(new Comment())->forPhoto($id)]); }
    public function create(): void { requireLogin(); render('photos/create',['title'=>'Upload photo']); }
    public function store(): void
    {
        requireLogin(); verifyCsrf(); $title=trim($_POST['title']??''); $description=trim($_POST['description']??''); $file=$_FILES['image']??null; $errors=[];
        if ($title==='' || mb_strlen($title)>200) $errors[]='Title is required and must be under 200 characters.';
        $uploadErrors=[UPLOAD_ERR_INI_SIZE=>'The image is larger than the server upload limit.',UPLOAD_ERR_FORM_SIZE=>'The image is larger than the form upload limit.',UPLOAD_ERR_PARTIAL=>'The image upload was interrupted.',UPLOAD_ERR_NO_FILE=>'Choose an image to upload.',UPLOAD_ERR_NO_TMP_DIR=>'The server temporary upload folder is missing.',UPLOAD_ERR_CANT_WRITE=>'The server cannot write the uploaded image.',UPLOAD_ERR_EXTENSION=>'A server extension stopped the upload.'];
        if (!$file || $file['error']!==UPLOAD_ERR_OK) $errors[]=$uploadErrors[$file['error']??UPLOAD_ERR_NO_FILE]??'Could not upload the image.';
        $allowed=['image/jpeg'=>'jpg','image/png'=>'png','image/gif'=>'gif','image/webp'=>'webp']; $mime=''; if ($file && isset($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) { $mime=function_exists('mime_content_type')?mime_content_type($file['tmp_name']):(new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']); } if ($file && $file['error']===UPLOAD_ERR_OK && !isset($allowed[$mime])) $errors[]='Only JPG, PNG, GIF, and WEBP images are allowed.'; if ($file && $file['error']===UPLOAD_ERR_OK && $file['size']>5*1024*1024) $errors[]='Maximum file size is 5MB.';
        if ($errors) { render('photos/create',['title'=>'Upload photo','errors'=>$errors]); return; }
        $uploadDirectory=__DIR__.'/../public/images/uploads/'; if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory,0775,true)) { render('photos/create',['title'=>'Upload photo','errors'=>['Could not create the uploads directory. Create public/images/uploads manually and try again.']]); return; } if (!is_writable($uploadDirectory)) { render('photos/create',['title'=>'Upload photo','errors'=>['The uploads directory is not writable. Check folder permissions and try again.']]); return; }
        $name=bin2hex(random_bytes(16)).'.'.$allowed[$mime]; $target=$uploadDirectory.$name; if (!move_uploaded_file($file['tmp_name'],$target)) { render('photos/create',['title'=>'Upload photo','errors'=>['Could not save uploaded file. Check that public/images/uploads exists and is writable.']]); return; }
        $id=(new Photo())->create(['user_id'=>currentUser()['id'],'file_name'=>$name,'title'=>$title,'description'=>$description]); flash('success','Photo uploaded successfully.'); redirect('photo/'.$id);
    }
    public function delete(int $id): void { requireLogin(); verifyCsrf(); $name=(new Photo())->deleteOwned($id,currentUser()['id']); if ($name) { $path=__DIR__.'/../public/images/uploads/'.$name; if (is_file($path)) unlink($path); flash('success','Photo deleted.'); } else flash('error','You can only delete photos you own.'); redirect('photos'); }
}
?>
