-- REICHMAT -- SQL ASSIGNMENT -- Selection -- 02/19/2017

#1 Find all films with maximum length and minimum rental duration (compared to all other films). #In other words let L be the maximum film length, and let R be the minimum rental duration in the table film. You need to find all films with length L and rental duration R. #You just need to return attribute film id for this query.

SELECT film_id FROM film
WHERE length = (
	SELECT MAX(length) FROM film)
AND rental_duration = (
	SELECT MIN(rental_duration) FROM film);

#2 We want to find out how many of each category of film ED CHASE has started in so return a table with category.name and the count #of the number of films that ED was in which were in that category order by the category name ascending (Your query should return every category even if ED has been in no films in that category).

SELECT c.name AS filmCategory, COUNT(f.name) AS filmCount
FROM 
(SELECT category.name FROM category) AS c
LEFT JOIN (
	SELECT category.name FROM category
	INNER JOIN film_category fc ON fc.category_id = category.category_id
	INNER JOIN film ON film.film_id = fc.film_id
	INNER JOIN film_actor fa ON film.film_id = fa.film_id
	INNER JOIN actor a ON a.actor_id = fa.actor_id
	WHERE a.first_name = 'ED' AND a.last_name = 'CHASE'
) AS f
ON c.name = f.name
GROUP BY c.name
ORDER BY c.name;

#3 Find the first name, last name and total combined film length of Sci-Fi films for every actor #That is the result should list the names of all of the actors(even if an actor has not been in any Sci-Fi films)and the total length of Sci-Fi films they have been in.

SELECT a.first_name AS 'First Name', a.last_name AS 'Last Name', SUM(f.length) AS 'Total Film Length'
FROM actor a
INNER JOIN film_actor fa ON a.actor_id = fa.actor_id
INNER JOIN film f ON fa.film_id = f.film_id
INNER JOIN film_category fc ON f.film_id = fc.film_id
INNER JOIN category c ON fc.category_id = c.category_id && c.name = 'Sci-Fi'
GROUP BY a.last_name ASC;

#4 Find the first name and last name of all actors who have never been in a Sci-Fi film

SELECT a.first_name, a.last_name 
FROM film f
INNER JOIN film_category fc ON f.film_id = fc.film_id
INNER JOIN category c ON fc.category_id = c.category_id
INNER JOIN film_actor fa ON f.film_id = fa.film_id
INNER JOIN actor a ON fa.actor_id = a.actor_id
WHERE c.name NOT IN (SELECT name FROM category WHERE name = 'Sci-Fi')
GROUP BY a.last_name ASC;

#5 Find the film title of all films which feature both KIRSTEN PALTROW and WARREN NOLTE #Order the results by title, descending (use ORDER BY title DESC at the end of the query) #Warning, this is a tricky one and while the syntax is all things you know, you have to think oustide #the box a bit to figure out how to get a table that shows pairs of actors in movies

SELECT f.title AS 'Films with Kirsten Paltrow and Warren Nolte'
FROM film f 
INNER JOIN film_actor fa ON fa.film_id = f.film_id
INNER JOIN actor a ON a.actor_id = fa.actor_id
WHERE a.actor_id =(
SELECT actor_id FROM actor WHERE first_name='WARREN' && last_name='NOLTE') || a.actor_id=(SELECT actor_id FROM actor WHERE first_name='KIRSTEN' && last_name='PALTROW')
GROUP BY f.title having (count(*) >= 2)
ORDER BY f.title DESC;
