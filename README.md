# mauricio-movies
## Requirements
* Magento: 2.3.4

## How to install
* command - composer require mauricio/module-movies
* link    - https://packagist.org/packages/mauricio/module-movies
* version - version 1.0.1


## After installing
* Is important to change the module's configuration data, which are: 
    * 'api_key' (authentication key in api movies), 
    * 'price' and 'stock' (these are the default values when creating a new product, <br />but it can be changed on the screen whenever you create a new product).

## About the module
1. Integration with the Movies API (admin): 
    1. The integration with the API is performed on the backend,through the same endpoint<br />used to access the movies index page. But, the pagination was done using JavaScript;
1. Importing products (admin): 
    1. An 'attribute_set' was created, which has the 'is_movie' attribute,<br />used to represent products that are movies;
1. InstallSchema and InstallData (admin): 
    1. In addition to InstallSchema for the creation of new product attributes,<br />a new table has also been created in the database, which serves to maintain<br />the data regarding the movies that have been saved as favorites by customers;
1. 'Add to favorites' button: 
    1. Was appended to the product view through the file “product.info.addto”;
1. Screen to view the top 10 favorite movies (admin): 
    1. ui_components was used to create the table with the data.

## Screenshots
#### Custom admin navigation bar  <br /> 
![Custom admin navigation bar](https://github.com/xeruba/mauricio-movies/blob/main/readme_images/01_side_menu.png?raw=true) <br /> 

#### Module configurations  <br />
![Module configurations](https://github.com/xeruba/mauricio-movies/blob/main/readme_images/02_configuration_module.png?raw=true) <br /> 

#### Page to import movies  <br />
![Page to import movies](https://github.com/xeruba/mauricio-movies/blob/main/readme_images/03_index_import_movies.png?raw=true) <br /> 

#### Showing movies  <br />
![Showing movies](https://github.com/xeruba/mauricio-movies/blob/main/readme_images/04_list_products.png?raw=true) <br />

#### _Add Favorite button_ on the product page  <br />
![_Add Favorite button_ on the product page](https://github.com/xeruba/mauricio-movies/blob/main/readme_images/05_view_product.png?raw=true) <br /> 

#### _Top 10 Favorite Movies_ page  <br />
![_Top 10 Favorite Movies_ page](https://github.com/xeruba/mauricio-movies/blob/main/readme_images/06_admin_top_10_favorite.png?raw=true) <br /> 