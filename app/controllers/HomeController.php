<?php

namespace App\controllers;
use \Delight\Auth\Auth;
use \PDO;
use \League\Plates\Engine;
use \Tamtamchik\SimpleFlash\Flash;

class HomeController{

	private $auth, $pdo, $templates, $flash;

	public function __construct(Auth $auth, PDO $pdo, Engine $templates, Flash $flash){
		$this->auth = $auth;
		$this->pdo = $pdo;
		$this->templates = $templates;
		$this->flash = $flash;
	}

	public function register(){
		echo $this->templates->render('page_register', ['title' => 'Регистрация']);
	}

	public function register_edit(){
		//d($this->auth);die;
		try {
		    $userId = $this->auth->register($_POST['email'], $_POST['password'], $_POST['username'], function ($selector, $token) {
		    	try {
				    $this->auth->confirmEmail($selector, $token);
				}
				catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
				    die('Invalid token');
				}
				catch (\Delight\Auth\TokenExpiredException $e) {
				    die('Token expired');
				}
				catch (\Delight\Auth\UserAlreadyExistsException $e) {
				    die('Email address already exists');
				}
				catch (\Delight\Auth\TooManyRequestsException $e) {
				    die('Too many requests');
				}
		    });
		    flash()->message('Вы зарегистированы!');
		}
		catch (\Delight\Auth\InvalidEmailException $e) {
		    flash()->message('<strong>Уведомление!</strong> Неверный формат почтового ящика.', 'error');
		    //die('Invalid email address');
		}
		catch (\Delight\Auth\InvalidPasswordException $e) {
			flash()->message('<strong>Уведомление!</strong> Неверный пароль.', 'error');

		    //die('Invalid password');
		}
		catch (\Delight\Auth\UserAlreadyExistsException $e) {
			flash()->message('<strong>Уведомление!</strong> Этот эл. адрес уже занят другим пользователем.', 'error');

		    //die('User already exists');
		}
		catch (\Delight\Auth\TooManyRequestsException $e) {
			flash()->message('Too many requests', 'error');

		    //die('Too many requests');
		}
		echo $this->templates->render('page_register', ['title' => 'Регистрация']);
	}

	public function login(){

		echo $this->templates->render('page_login', ['title' => 'Войти']);
	}

	public function login_edit(){

		try {
		    $this->auth->login($_POST['email'], $_POST['password']);
		    flash()->message('Вход выполнен');
		    echo 'User is logged in';
		}
		catch (\Delight\Auth\InvalidEmailException $e) {
			flash()->message('<strong>Уведомление!</strong> Неверный email адрес.', 'error');
		    //die('Wrong email address');
		}
		catch (\Delight\Auth\InvalidPasswordException $e) {
			flash()->message('<strong>Уведомление!</strong> Неверный пароль.', 'error');
		    //die('Wrong password');
		}
		catch (\Delight\Auth\EmailNotVerifiedException $e) {
			flash()->message('<strong>Уведомление!</strong> Электронная почта не подтверждена.', 'error');
		    //die('Email not verified');
		}
		catch (\Delight\Auth\TooManyRequestsException $e) {
			flash()->message('<strong>Уведомление!</strong> Слишком много запросов.', 'error');
		    //die('Too many requests');
		}
		echo $this->templates->render('page_login', ['title' => 'Войти']);
	}

	public function users(){
		echo $this->templates->render('users', ['title' => 'Document']);
	}

	public function create_user(){
		echo $this->templates->render('create_user', ['title' => 'Document']);
	}

	public function create_user_edit(){
		d($_POST, $_FILES['file']);
	}
}