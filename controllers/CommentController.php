<?php
class CommentController
{
    public function store(int $photoId): void { requireLogin(); verifyCsrf(); $text=trim($_POST['comment']??''); if ($text==='') flash('error','Comment cannot be empty.'); elseif (mb_strlen($text)>1000) flash('error','Comment is too long.'); else { (new Comment())->create($photoId,currentUser()['id'],$text); flash('success','Comment added.'); } redirect('photo/'.$photoId); }
}
?>
