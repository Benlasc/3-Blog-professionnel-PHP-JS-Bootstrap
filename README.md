# Blog

Instructions to install the project

Step 1: Apache server configuration
Configure your Apache server to set the DocumentRoot to the "/Web" folder.

Step 2: Create the MySQL database and tables
Execute the SQL code in the file "bdd/code.sql" in your MySQL client.

Step 3: Configuration of the database connection
In the file "/lib/OCFram/PDOFactory.php", enter the required informations to connect to your MySQL database (dsn, username, password).
