# Projet8 - ToDoList

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/469b12a1b5d6467ea88f9dcd9129f428)](https://app.codacy.com/gh/valh-runner/oc_projet8/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)


Improvement of an existing Todo List application named Todo & Co.
This initially involves migrating the application to an actively supported version of the Symfony framework.
Then proceed to correct some identified anomalies.
Finally, it is expected to add new features.
These new features consist of the implementation of unit and functional tests
and in setting up access authorization to certain parts of the application and certain actions depending on the user's role.

### Environment used at the current state
-   Apache 2.4.58.1
-   PHP 8.3.1
-   MySQL 5.7.24
-   Composer 2.6.5
-   Git 2.42.0

### Library used at the current state
-   Symfony 6.4.3 LTS

## Installation

### Environment setup

It is necessary to have an Apache / Php / Mysql environment.\
Depending on your operating system, choose your own:
-   Windows : WAMP (<http://www.wampserver.com>)
-   MAC : MAMP (<https://www.mamp.info/en/mamp>)
-   Linux : LAMP (<https://doc.ubuntu-fr.org/lamp>)
-   Cross system: XAMP (<https://www.apachefriends.org/fr/index.html>)

Symfony 6.4 requires at least PHP 8.1.0 but prefer to have version 8.3.1 at minimum to complies with dependances needs\
MySQL have to be in 5.7 version.\
Make sure PHP is in the Path environment variable.

You need an installation of Composer.\
So, install it if you don't have it. (<https://getcomposer.org>)

If you want to use Git (optional), install it. (<https://git-scm.com/downloads>)

### Project files local deployement

Manually download the content of the Github repository to a location on your file system.\
You can also use git.\
In Git, go to the chosen location and execute the following command:
```
git clone https://github.com/valh-runner/oc_projet8.git todoco

```

Then, open a command console and go to the application root directory.\
Install dependencies by running the following command:
```
composer install
```

### Database generation
Launch the previously installed software containing a Mysql server.

Change the database connection values for correct ones in the .env file.\
Like the following example with a bilemo named database to create:
```
DATABASE_URL="mysql://root:@127.0.0.1:3306/todoco?serverVersion=5.7"
```

In a new console placed in the root directory of the application,\
launch the creation of the database:
```
symfony console doctrine:database:create
```

Then, build the database structure using the following command:
```
symfony console doctrine:migrations:migrate
```

Finally, load the initial dataset of products and example users into the database.\
Use the following command:
```
symfony console doctrine:fixtures:load
```

## Launch a web server

### By the Symfony Local Web Server
Place you in project root and launch the symfony server with the following command:
```
symfony serve
```
Leave this console open.

### By a virtualhost
If you don't wan't to use the Symfony Local Web Server, you can use your Apache/Php/Mysql environment in the classic way.\
This by configuring a virtualhost in which to place the project.

## Use the application

You can log in with as a regular user or as an admin.
Initial accounts are created by fixtures.\
Users accounts credentials are visible in fixtures files.

In addition to be able to do regular actions,\
Admin users can manage regular users\
and modify and delete their tasks but only if owned by the anonym user.
It represent the special account who become owner of all ancient tasks.
Ancient tasks existing before tasks become binded to a user who own it.

## Troubleshooting

If some disfunctionments appear or a file is missing, check your anti-virus quarantine.\
Prefer to set, in your anti-virus, an exclusion for the application folder.
