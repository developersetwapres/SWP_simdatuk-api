## About Simdatuk

![Simdatuk](/public/img/logo.svg)

SIMDATUK is an acronym for Aplikasi Sistem Informasi Manajemen Data Dukungan Kepegawaian. This application serves as a tool for profiling employee data, ensuring efficiency, measurability, and comprehensiveness.

## Table of Contents

[[_TOC_]]

## Prerequisites

-   GIT [download here](https://git-scm.com/)
-   Docker [download here](https://www.docker.com/products/docker-desktop/)

## How to Setup

1. Clone this source code to your directory with command:

```bash
git clone http://git.ekuator.id/project/setneg/simdatuk/api.git
```

2. Navigate to your source code directory.
3. Copy the **.env.example** file with the following command:

```bash
cp .env.example .env
```

4. Edit and configure file **.env** file to match the environment you will be using, especially updating the website and database configurations.
5. Open your Docker application.
6. Run the following command in your source code directory to start the application:

```bash
docker-compose up -d
```

7. Access PhpMyAdmin at [http://localhost:8080](http://localhost:8080).
8. Create a database with the same name as specified in the **.env** file.
9. Your local SIMDATUK application is now ready to use on [http://localhost](http://localhost).
10. Happy coding! 🎉🎉🎉 and keep up the great work! 🚀
11. Note: for access webmail visit this link [http://localhost:1080](http://localhost:1080).

## API Collection

For a list of used endpoints, you can use this api documentation [here](https://localhost/docs).

## Database Diagram

To see database diagram, copy data on [**diagram.txt**](/dbdiagram.txt) file and paste on [https://dbdiagram.io](https://dbdiagram.io)

## Infrastructure

The following is the recommendation infrastructure used in constructing in production
![SIMDATUK](/infrastructure.jpg)

## Specification

| Item             | Staging        | Production     |
| ---------------- | -------------- | -------------- |
| Processor        | 2vCPU          | 2vCPU          |
| RAM              | 2GB            | 4GB            |
| Storage          | 10GB           | 10GB           |
| Operating System | Ubuntu 12.04   | Ubuntu 12.04   |
| Docker           | Latest version | Latest version |

## How to Deploy to Production

```bash
cd /var/www/api
git pull origin main
```

Update .env file

```bash
docker build . -t simdatuk-api —no-cache
docker rm simdatuk-api --force
docker run -d -p 8080:80 --name simdatuk-api -v /var/www/app/api:/storage --network simdatuk_network --restart always simdatuk-api
```

Run migration

```bash
docker exec -it /bin/bash simsdatuk-api
php artisan migrate
```

## Code Style

To adhere to good coding standards, follow the references below:

-   [PSR-12](https://www.php-fig.org/psr/psr-12/)
-   [Laravel best practices](https://github.com/alexeymezenin/laravel-best-practices)

## GIT Style

For naming branches and adhering to the Git style guide, please refer to the following documentation [here](https://www.conventionalcommits.org/en/v1.0.0/).

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://git.ekuator.id/project/setneg/simdatuk/api/-/tags).

[Table of Contents](#table-of-contents)
