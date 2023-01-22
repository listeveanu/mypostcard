# My Postcard Design listing

Shows a list of designs.

## Description

I used PHP 8.2 to build the project. The structure takes advantages of OOP. The entry point is `/public/index.php`, witch act like a router.
There are 3 routes: `index`, `thumbnail` and `createPdf`.

Each of them has a corresponding controller.

There is a HttpService and a View class.

The template is inside `/src/template/index.php`.

## Getting Started

### Installing

* How to run the program
```
git clone git@github.com:listeveanu/mypostcard.git
cd mypostcard
docker-compose build
docker-compose up -d
```

* For accessing the container
```
docker exec -it design_store bash
composer update
```

* Accessing the project
```
http://localhost:8000/
```
