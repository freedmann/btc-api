# Тестовое задание

## Решение задания 1

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

## Решение задания 2

Токен для авторизации:
S7dh-S8_2jdh76d35tsGDfs-sj_jdSD_88SdKj7-2d7G-2LMv_78AuI-3$31J7F
