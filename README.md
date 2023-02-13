# Test task

## Solution of task 1

```

SELECT 
	users.id AS Id, CONCAT (users.first_name, ' ', users.last_name) AS Name, books.author as author , group_concat(books.name) as books  
FROM  
	`user_books`
INNER JOIN `users` ON user_books.user_id = users.id 
INNER JOIN `books` ON user_books.book_id = books.id
WHERE users.age BETWEEN 7 and 17 
GROUP BY Id
HAVING (COUNT(books.name)=2) AND (COUNT(DISTINCT author)=1);

```

## Solution of task 2

To run application in Docker, you need a linux machine (Ubuntu 22 preferred) with installed Docker, PHP 8.1, git, composer

create a new folder to run application

```
mkdir testapp
cd testapp

```

clone application from guthub

```
git clone https://github.com/freedmann/btc-api.git
cd btc-api
composer install

```

run application with docker
```
sail up -d

```

run tests

```
sail test --filter Feature

```

for testing API with Postman/Insomnia use token for Bearer auth : 
```
S7dh-S8_2jdh76d35tsGDfs-sj_jdSD_88SdKj7-2d7G-2LMv_78AuI-3$31J7F
```


