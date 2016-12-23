<?php
    return array(
		'tasks/([0-9]+)' => 'task/view/$1',
		'tasks/add' => 'task/add',
		'tasks/updateDone/([0-9]+)/([0-1])' => 'task/updateDone/$1/$2',
		'tasks/done' => 'task/done',
		'tasks/chekeddDone/([0-1]+)' => 'task/chekeddDone/$1',
		'tasks/edit/([0-9]+)' => 'task/edit/$1',
		'tasks' => 'task/list',

// Пользователь:
    'user/register' => 'user/register',
    'user/login' => 'user/login',
    'user/logout' => 'user/logout',

//Пагинация
	'page/([0-9]+)'=> 'site/index/$1',

// Главная страница
    'index.php' => 'site/index', // actionIndex в SiteController
    '' => 'site/index', // actionIndex в SiteController
	);
