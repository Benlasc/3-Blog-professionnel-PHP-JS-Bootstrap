# Professional blog 

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/57178e8e64ea414ba80c3894b1b295af)](https://app.codacy.com/gh/Benlasc/4-Blog-professionnel-PHP-JS-Bootstrap?utm_source=github.com&utm_medium=referral&utm_content=Benlasc/4-Blog-professionnel-PHP-JS-Bootstrap&utm_campaign=Badge_Grade_Settings)

Pedagogical project: create a professional blog using PHP Vanilla.

Back-end: Vanilla PHP - MySQL 

Front-end: Bootstrap - Vanilla JavaScript 

## Instructions to install the project

Download zip files or clone the project repository with github ([see GitHub documentation](https://docs.github.com/en/github/creating-cloning-and-archiving-repositories/cloning-a-repository)). 

### __Step 1: Libraries installation__
Open a terminal and do "composer install" to download the necessary libraries.

### __Step 2: Apache server configuration__
Configure your Apache server to set the DocumentRoot to the "/Web" folder.

### __Step 3: Create the MySQL database and tables__
Execute the SQL code of "bdd/code.sql" in your MySQL client.

### __Step 4: Configuration of the database connection__
In the file "/lib/OCFram/PDOFactory.php", enter the required informations to connect to your MySQL database (dsn, username, password).

### __Step 5 (optional): Seed your database with test data__
Open a terminal and do "./vendor/bin/phinx seed:run" to fill your database with fake data.
