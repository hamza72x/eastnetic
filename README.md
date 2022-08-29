# Running

-   Create `.env` file
-   Run `Docker`
-   Run a postgres container `$ make db_dev`
-   Run a redis container `$ make redis_dev`
-   Run `$ make migrate` and `$ make seed`
-   Run the app `$ make dev`
-   App should be running on port: 8000

# Other

-   Test the app using `$ make test`
-   Routes are defined in `api.php`
-   More information about these make commands can be found in [./Makefile](./Makefile)
-   Why every commands are in Makefile? Because I like to gather all the required commands in one place so that we don't have to remember/forget about commands in future.
