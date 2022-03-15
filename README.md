# [Geminer](https://pastebin.com/raw/9gJBUfGp)
### Geminer is a number simulator where users mine, sell, and decorate virtual canvases with gems.

**Note**: This ain't out yet. Check out the [beta](https://geminer-beta.ocean.lol) for a demo.

Below are instructions on how to set up Geminer for testing purposes or whatever else.

# Setup
## Requirements
I dunno lol
- php 7.3+
- [composer](https://getcomposer.org/)
- sqlite3
- pdo-sqlite3
- [mbstring](https://www.php.net/manual/en/mbstring.installation.php)

## Instructions
Do these and you'll be good to go.
1. Navigate to the Geminer directory
2. Copy `config/config.example.php` to `config/config.php` and edit whatever you need within the file
3. Run `composer install`
4. Run `sqlite3 data.db` then do `.read createdb.sql`
5. Set up a web server using the `public` directory as the root
6. Configure the web server so that php files can be accessed without the ".php" extension (e.g. `/announcements.php` can be viewed from `/announcements`)
7. Create an account on the new website
8. Run `sqlite3 data.db` then do `UPDATE users SET is_admin = 1;` to make yourself an admin
 
### Database manager
To set up phpLiteAdmin to manage the database easier, follow these steps.
1. Create a directory called `dbm` in `public/admin`
2. [Download phpLiteAdmin](https://www.phpliteadmin.org/download/)
3. Put `phpliteadmin.php` from the downloaded zip file into the newly created `dbm` directory and rename it to `index.php`
4. Create another file in `public/admin/dbm` named `phpliteadmin.config.php` with [this code](https://gist.github.com/975miles/328df142f9b3dae7f1bb771b911e360d) as its contents (you can omit lines 2-3 if this is a local installation, otherwise you must have already made an admin user)
5. Change the password in `phpliteadmin.config.php` if you need to
