# JelaDKP

JelaDKP is web-application built to make it easier to handle DKP-like systems in gaming-communities.

JelaDKP is built on top of [Laravel](https://laravel.com/ "Laravel framework"). Any original work on the framework is not my own work.

The code written for JelaDKP is licensed under GPL-3.0. Similiar and related code can be found at [SoulDKP](https://github.com/sawyl/soulDkp "SoulDkp Git repository") repository. Read background section for further information.

These codes can be found under folders:

```
/app/Http/Controllers/*
/app/Models/*
/database/migrations/*
/database/seeds/*
/public/* #!!all but /assets/external!!
/resource/lang/*
/resource/views/*
/routes/web.php
```
*Note: \* means the whole folder is JelaDKP code*

## Background

JelaDKP is part of master thesis project. Two separate web-applications were created for the thesis.

First project that was created is [SoulDKP](https://github.com/sawyl/soulDkp "SoulDKP Git repository"). 
SoulDKP was done with minimal time invested before getting started, and not using any design patterns as help.

This project (JelaDKP) was the second created project.
JelaDKP was done with proper preparations. Two design patterns were considered when creating this project:
    
	1. MVC-model
	2. Template method model


## Install instructions.
    1. Set up your web environment
	    1.1. Fetch the application files into the folder you want to use as your public html folder.
	    1.2. Create any database supported by Laravel.
		1.3. Install the application like regular Laravel application https://laravel.com/docs/5.5/installation
		
	2. Set the default login that you want to use for your application into \database\seeds\DefaultUsersSeeder.php
	3. Connect to the install folder via SSH
	4. Initialize the database with commands:
	    4.1. php artisan migrate
		4.2. php artisan db:seed
	5. Enjoy.