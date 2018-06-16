Todo app API
=====
tags: Symfony 4.1, Docker, API Platform

App is developed based on Symfony 4 skeleton. API Platform serves for fast CRUD API implementation, nice Swagger docs covering. 

LexikJWTAuthenticationBundle provides token-based authentication.
Some of key services are covered with unit tests.

Some works in progress: no tasks collection filtering by user

First Setup for Developer
-----------

**Step 1.** Specify environment variables by creating a `.env` file 

**Step 2.** After pulling use `docker-compose up -d` to build, (re)create, start, containers for a service.

**Step 3.** Use `composer` for dependency management

`composer install`

**Step 4.** Setup LexikJWTAuthenticationBundle (generate ssh keys), see 
https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md#installation

**Step 5.** Go to 0.0.0.0/api/docs to start work with API
