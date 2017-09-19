Dienstplaner / Roster manager
===========
PHP-based web application for creating a roster. This is a fork from a German repository, so most of the comments/database entries are currently written in German.


Requirements
------------
- `php`
- `mysql`

Bootstrapping
------------

Create a database `dienstplaner` inside mysql. Don't forget to enter your credentials in `/inc/config.php`. 
Default values are user "root" and password "root".

After you have created the database, run `/ext/dienstplaner.sql` for creating the required tables.

Defaults:
-------
Following accounts will be created
<table>
  <tr>
    <th align=left>Username</th>
    <th>Password</th>
  </tr>
  <tr>
    <td><tt>admin@dienstplaner.de</tt></td>
    <td align=right><tt>admin</tt></td>
  </tr>
</table>
