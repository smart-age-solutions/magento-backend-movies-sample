Install this package using the composer:
	https://packagist.org/packages/lima/movies (version 1.0.4)

Considerations:
	- The module was built following the best practices of Magento, using ui components, ko javascript, saving data with queue repository, interfaces and queue with cron.
	- I tried so hard to use ui components to retrieve items from the API, but nothing works, I got overlaping block collection (without ui components), but the pagination, the total of items and the filter were not working well. After many tries, my best solution for this was to use a block model and ko scripts to create a table and an ajax form to retrieve items from the API (keeping magento 2 admin theme at most). All other admin grid uses best practices with full-featured ui components (except in-line editing).
	- I chose to use the queue system with cron to import products, but the queue import can be forced with the bulk action import (admin grid queue).
	- I have some versions on packgist because I never put a projeto into packgist and I had to do some tests.

This module contains the following resources:
	1 - Set a default value for products like 'price', 'stock' and 'attribute_set'
	2 - Define the default search terms, such as 'language' and 'adult'
	3 - Import movie with custom price or stock (this overlaps with default 'price' or 'price'), you can override these values in the search results table, just before import
	4 - Movies import through the queuing system (using cron). After searching and importing the selected results, this movie goes to a queue import and then becomes a product
	5 - The queuing system uses cron to import products
	6 - You can add a movie as one of your favorite in the catalog page view clicking "Add to favorites" button next to "Add to wish list" or "Add to compare"
	7 - This module can be installed with the composer (https://packagist.org/packages/lima/movies)
	8 - ACL for all resources

Demo images:

* Menu &nbsp;
![Menu](https://github.com/lima195/Lima_Movie/blob/master/images/01_menu.png?raw=true)

* System configuration &nbsp;
![System configuration](https://github.com/lima195/Lima_Movie/blob/master/images/02_config.png?raw=trueraw=true)

* Search Movie Results &nbsp;
![Search Movie Results](https://github.com/lima195/Lima_Movie/blob/master/images/03_search_movie.png?raw=true)

* Setup custom price &nbsp;
![Setup custom price](https://github.com/lima195/Lima_Movie/blob/master/images/04_setup_custom_price.png?raw=true)

* Confirm Import &nbsp;
![Confirm Import](https://github.com/lima195/Lima_Movie/blob/master/images/05_import_confirm.png?raw=true)

* Import Movie Queue (waiting cron process) &nbsp;
![Import Movie Queue (waiting cron process)](https://github.com/lima195/Lima_Movie/blob/master/images/06_import_movie_queue.png?raw=true)

* Force Import &nbsp;
![Force Import](https://github.com/lima195/Lima_Movie/blob/master/images/07_import_movie_queue_force.png?raw=true)

* Import Success &nbsp;
![Import Success](https://github.com/lima195/Lima_Movie/blob/master/images/08_import_movie_success.png?raw=true)

* Favorite List Before &nbsp;
![Favorite List Before](https://github.com/lima195/Lima_Movie/blob/master/images/09_movie_favorite_list_before.png?raw=true)

* Search Movie Results (frontend) &nbsp;
![Search Movie Results (frontend)](https://github.com/lima195/Lima_Movie/blob/master/images/10_movie_search_result_frontend.png?raw=true)

* Product View &nbsp;
![Product View](https://github.com/lima195/Lima_Movie/blob/master/images/11_movie_view.png?raw=true)

* Added as favorite &nbsp;
![Added as favorite](https://github.com/lima195/Lima_Movie/blob/master/images/12_movie_add_favorite.png?raw=true)

* Favorite List Now (This collection has a group by product_id, count and limit 10 in _initSelect() on collection file) &nbsp;
![Favorite List Now (This collection has a group by product_id, count and limit 10 in _initSelect() on collection file)](https://github.com/lima195/Lima_Movie/blob/master/images/13_movie_favorite_list.png?raw=true)