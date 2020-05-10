## Api
Deployment:  This is written using a standard laravel installation.  Deployment can be done by cloning this repo,
and then running composer install.  Once all packages are installed, run php artisan server to start the api server.

API: The api can be accessed at localhost:8000/api/todos via a GET request

Data can be filtered by passing in the parameter name and value to be filtered.  Sub-parameters can be filtered by passing
them in array notation.

ie: /api/todos?user[company][name]=Romaguera-Crona&user[email]=Sincere@april.biz&title=laboriosam%20mollitia%20et%20enim%20quasi%20adipisci%20quia%20provident%20illum

Tests:  Tests are contained in tests/Feature/TodoApiTest.php 

Tests can be run via ./vendor/bin/phpunit



## Assignment

Create an API endpoint that displays a list of todos and their associated user information

• The endpoint must download a list of todos from
https://jsonplaceholder.typicode.com/todos , and a list of users from
https://jsonplaceholder.typicode.com/users

• The endpoint must merge the list of users into the list of post and serve out the data

• Enable search on one or more of the post fields or linked fields (eg. title, username)

• Bonus points for tested code

Deliverables
1. Code uploaded to github/bitbucket/other, and link to the repo

2. Code deploys on an assessable server and the URL

3. A brief overview of how you approached the task, and any design decisions you
made
