# Blog
Requirements :

-> PHP versions needed: PHP~8
-> Composer must be installed

Instructions to install the project

Step 1: Libraries installation
Extract the project into a folder. Open a terminal and do  "composer update" to download the necessary libraries.

Step 2: Apache server configuration
Configure your Apache server to set the DocumentRoot to the "/Web" folder.

Step 3: Create the MySQL database and tables
Execute the SQL code of "bdd/code.sql" in your MySQL client.

Step 4: Configuration of the database connection
In the file "/lib/OCFram/PDOFactory.php", enter the required informations to connect to your MySQL database (dsn, username, password).
