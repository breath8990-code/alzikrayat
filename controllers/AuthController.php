<?php
class AuthController
{
    public function showLogin(): void { render('auth/login', ['title'=>'Login', 'lastLogin'=>lastLogin()]); }
    public function showRegister(): void { render('auth/register', ['title'=>'Create account']); }
    public function register(): void
    {
        verifyCsrf(); $first=trim($_POST['first_name']??''); $last=trim($_POST['last_name']??''); $email=strtolower(trim($_POST['email']??'')); $password=$_POST['password']??'';
        $errors=[]; if (!preg_match('/^[a-zA-Z]{1,50}$/',$first)) $errors[]='First name must contain letters only (max 50).'; if (!preg_match('/^[a-zA-Z]{1,50}$/',$last)) $errors[]='Last name must contain letters only (max 50).'; if (!filter_var($email,FILTER_VALIDATE_EMAIL)) $errors[]='Enter a valid email.'; if (strlen($password)<8) $errors[]='Password must be at least 8 characters.';
        $userModel=new User(); if (!$errors && $userModel->findByEmail($email)) $errors[]='Email is already registered.';
        if ($errors) { render('auth/register',['title'=>'Create account','errors'=>$errors]); return; }
        $userModel->create(['first_name'=>$first,'last_name'=>$last,'email'=>$email,'password'=>password_hash($password,PASSWORD_BCRYPT),'location'=>trim($_POST['location']??''),'description'=>trim($_POST['description']??''),'occupation'=>trim($_POST['occupation']??'')]); flash('success','Account created. Please login.'); redirect('login');
    }
    public function login(): void
    {
        verifyCsrf(); $email=strtolower(trim($_POST['email']??'')); $user=(new User())->findByEmail($email);
        if (!$user || !password_verify($_POST['password']??'', $user['password'])) { flash('error', 'Invalid email or password. Please try again.'); redirect('login'); }
        session_regenerate_id(true); $_SESSION['user']=['id'=>$user['id'],'first_name'=>$user['first_name'],'last_name'=>$user['last_name'],'email'=>$user['email']]; setcookie('last_login', date('Y-m-d H:i:s'), ['expires'=>time()+604800,'path'=>'/','httponly'=>true,'samesite'=>'Lax']); flash('success','Welcome back, '.e($user['first_name']).'!'); redirect('');
    }
    public function logout(): void { $_SESSION=[]; session_destroy(); redirect(''); }
}
?>
