## Setup
This guide assumes the user has setup a LAMP stack on the server host, and has configured it correctly. To setup pixelstats on a production environment, follow these instructions:
1. git clone https://github.com/tysonlmao/pixelstats
2. Give the remote / local server write access to the entire project, so you don't run into permission based issues
```bash
# assumes the user is using the default user in ubuntu
sudo chown -R ubuntu:ubuntu pixelstats
```
4. Create a config.php inside the /includes folder, and define the following variables
```php
define('RECAPTCHA_SECRET_KEY', 'YOUR_RECAPTCHA_KEY_HERE');
define('PDO_DBNAME', 'pixelstats');
define('PDO_DBUSER', 'root');
define('PDO_DBPASSWORD', 'root');
```
3. Create a config.json inside of the /server directory, and define the following key
```json
{
"HYPIXEL_API_KEY": "YOUR_HYPIXEL_API_KEY_HERE"
}
```
4. Create a /uploads directory for images to be stored from the posts feed in the root of the project.
5. On your server host, create a new screen window, navigate to the /server folder inside the pixelstats application, and startup the server, this will create a players.json tinydb database. It should be found at https://localhost:8073. After starting the server, you can exit the screen by pressing ^A ^D on Mac.
```bash
screen -S pixelstats-api
# navigate to pixelstats folder
pip install -r requirements.txt
sudo python3 server.py
```
6. Use the sql in this gist to import to phpMyAdmin (if applicable) to generate a skeleton of the database: https://gist.github.com/tysonlmao/ab936fb3497897ee5104db0408a24080
7. To give yourself admin permissions, first create a user using register.php, login and you should be able to access settings, where it says your permission level is "User". Using phpMyAdmin, in the users table, use the enum list to change the permission level to "Admin". To do this with SQL in the command-line, run the following SQL statement
```sql
UPDATE users SET user_role = 'Admin' WHERE id = [user_id];
```
8. Thats it! You have completed the setup for the pixelstats application. Enjoy!