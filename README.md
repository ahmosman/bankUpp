![Alt text](public/images/bankupp-logo.jpg)

# BankUpp

Banking application <br><br>
![Alt text](https://img.shields.io/badge/release%20date-march%202022-blueviolet)

## About

In this banking application there are three roles: an admin, a banker and a user. The banker can manage the users,
whereas the admin can manage the users, as well as the bankers. After registering and confirming an e-mail address the
users get a banking account with a starting bonus. They can choose from three types of accounts and add as many accounts
as they want with different starting bonuses. The users can transfer money via the account or a phone number both to
their own accounts and to other user's ones . The users can also decide which account the money should be transferred to
when someone sends it to their phone number.

## Setup

```bash
# Clone this repository
$ git clone https://github.com/ahmosman/bankUpp

# Go into the repository
$ cd bankUpp

# Install dependencies
$ composer install

# Run database via docker-compose
$ docker-compose up -d

# Import tables from prepared SQL file to database
$ docker-compose exec db bash -c  "mysql -uroot -ppasswd bankupp < /database/bankupp-db.sql"

# Clear cache
$ symfony console cache:clear

# Run the server and go to URL where web server is listening
$ symfony serve -d

#NOTE: You can run the database on your own, as well as the web server.
```

### Sendgrid API Setup

If you would like to enable users registration you need to replace API_KEY_ID and API_KEY inside the .env file with your
own Sendgrid API key ID and API key.

```bash
# .env

MAILER_DSN=sendgrid://SG.API_KEY_ID.API_KEY@default
```

You can get this by creating API Key for free on <a href="https://sendgrid.com">Sendgrid</a>.

If you just want to try this app out you can use premade accounts presented below.

## Premade accounts

<table>
<tr>
    <th>E-mail</th>
    <th>Password</th>
    <th>Role</th>
</tr>
<tr>
    <td>admin@bankupp.com</td>
    <td>Qwerty1$</td>
    <td>Admin</td>
</tr>
<tr>
    <td>banker@bankupp.com</td>
    <td>Qwerty1$</td>
    <td>Banker</td>
</tr>
<tr>
    <td>rob.user@bankupp.com</td>
    <td>Qwerty1$</td>
    <td>User</td>
</tr>
<tr>
    <td>ann.user@bankupp.com</td>
    <td>Qwerty1$</td>
    <td>User</td>
</tr>


</table>

## Requirements

- PHP 8.0+
- Composer
- Docker
- Symfony CLI

## Technologies used

- Symfony 5
- CSS
- JS

