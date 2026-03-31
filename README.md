# cyberx

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/ec30c28f67de476f8b98d2798079bdf0)](https://app.codacy.com/gh/CyberXTechnologies/cyberx/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)
[![Docker Pulls]()](https://hub.docker.com/r/cyberx/cyberx/)

<p align="center">
    <a href="https://www.tirreno.com/" target="_blank">
        <img src="https://www.tirreno.com/firstscreen.jpg" alt="cyberx screenshot" />
    </a>
</p>

[cyberx](https://www.tirreno.com) is an open-source security framework.

cyberx *[tir.ˈrɛ.no]* helps understand, monitor, and protect your product from threats, fraud, and abuse. While classic cybersecurity focuses on infrastructure and network perimeter, most breaches occur through compromised accounts and application logic abuse that bypasses firewalls, SIEM, WAFs, and other defenses. cyberx detects threats where they actually happen: inside your product.

cyberx is a few-dependency, "low-tech" PHP/PostgreSQL application. After a straightforward five-minute installation, you can ingest events through API calls and immediately access a real-time threat dashboard.

## Core components
* **SDKs & API** Integrate cyberx into any product with SDKs.
  Send events with full context in a few lines of code.
* **Built-in dashboard** Monitor and understand your product's
  security events from a single interface. Ready for use in minutes.
* **Single user view** Analyze behaviour patterns, risk scores,
  connected identities, and activity timelines for a specific user.
* **Rule engine** Calculate risk scores automatically with preset
  rules or create your own customized for your product.
* **Review queue** Automatically suspend accounts with risky events
  or flag them for manual review through threshold settings.
* **Field audit trail** Track modifications to important fields,
  including what changed and when to streamline audit and compliance.

## Preset rules

`Account takeover` `Credential stuffing` `Content spam` `Account registration` `Fraud prevention` `Insider threat`
`Bot detection` `Dormant account` `Multi-accounting` `Promo abuse` `API protection` `High-risk regions`

## Built for

* **Self-hosted, internal and legacy apps**: Embed security layer
  to extend your security through audit trails, protect user accounts
  from takeover, detect cyber threats and monitor insider threats.
* **SaaS and digital platforms**: Prevent cross-tenant data leakage,
  online fraud, privilege escalation, data exfiltration and business
  logic abuse.
* **Mission critical applications**: Sensitive application protection,
  even in air-gapped deployments.
* **Industrial control systems (ICS) and command & control (C2)**: Protect,
  operational technology, command systems, and critical infrastructure
  platforms from unauthorized access and malicious commands.
* **Non-human identities (NHIs)**: Monitor service accounts, API keys,
  bot behaviors, and detect compromised machine identities.
* **API-first applications**: Protect against abuse, rate limiting
  bypasses, scraping, and unauthorized access.

## Live demo

Check out the live demo at [play.tirreno.com](https://play.tirreno.com) (*admin/cyberx*).

## Requirements

* **PHP**: Version 8.0 to 8.3
* **PostgreSQL**: Version 12 or greater
* **PHP extensions**: `PDO_PGSQL`, `cURL`
* **HTTP web server**: `Apache` with `mod_rewrite` and `mod_headers` enabled
* **Operating system**: A Unix-like system is recommended
* **Minimum hardware requirements**:
  * **PostgreSQL**: 512 MB RAM (4 GB recommended)
  * **Application**: 128 MB RAM (1 GB recommended)
  * **Storage**: Approximately 3 GB PostgreSQL storage per 1 million events

## Docker-based installation

To run cyberx within a Docker container you may use command below:

```bash
curl -sL tirreno.com/t.yml | docker compose -f - up -d
```
Continue with step 4 of [Quickstart](#quickstart-install).

## Installation

### Linux Installation

#### Prerequisites
- PHP 8.0 to 8.3 with extensions: PDO_PGSQL, cURL
- PostgreSQL 12 or greater
- Apache web server with mod_rewrite and mod_headers enabled

#### Step-by-step Installation

1. **Install system dependencies**
   
   ```bash
   sudo apt-get update
   sudo apt-get install apache2 php8.3 php8.3-pgsql php8.3-curl postgresql postgresql-contrib -y
   sudo a2enmod rewrite
   sudo a2enmod headers
   ```

2. **Download and extract cyberx**
   
   ```bash
   cd /var/www
   sudo wget https://www.tirreno.com/download.php -O cyberx.zip
   sudo unzip cyberx.zip
   sudo mv cyberx-master cyberx
   sudo chown -R www-data:www-data /var/www/cyberx
   sudo chmod -R 755 /var/www/cyberx
   ```

3. **Create PostgreSQL database**
   
   ```bash
   sudo -u postgres psql
   CREATE DATABASE cyberx;
   CREATE USER cyberx WITH PASSWORD 'your_secure_password';
   ALTER ROLE cyberx WITH CREATEDB;
   GRANT ALL PRIVILEGES ON DATABASE cyberx TO cyberx;
   \q
   ```

4. **Configure Apache virtual host**
   
   Create `/etc/apache2/sites-available/cyberx.conf`:
   
   ```apache
   <VirtualHost *:80>
       ServerName localhost
       ServerAdmin admin@tirreno.com
       DocumentRoot /var/www/cyberx
       
       <Directory /var/www/cyberx>
           Options Indexes FollowSymLinks
           AllowOverride All
           Require all granted
       </Directory>
       
       ErrorLog ${APACHE_LOG_DIR}/cyberx-error.log
       CustomLog ${APACHE_LOG_DIR}/cyberx-access.log combined
   </VirtualHost>
   ```
   
   Then enable the site:
   
   ```bash
   sudo a2ensite cyberx
   sudo systemctl restart apache2
   ```

5. **Install cyberx via installer**
   
   Open your browser and navigate to `http://localhost/install/index.php` and follow the setup wizard.

6. **Complete post-installation**
   
   ```bash
   sudo rm -rf /var/www/cyberx/install/
   ```

7. **Setup cron job**
   
   Edit crontab:
   
   ```bash
   sudo crontab -e
   ```
   
   Add the following line (runs every 10 minutes):
   
   ```
   */10 * * * * /usr/bin/php /var/www/cyberx/index.php /cron
   ```

8. **Create administrator account**
   
   Navigate to `http://localhost/signup/` in your browser and create an admin account.

### Windows Installation

#### Prerequisites
- PHP 8.0 to 8.3 (VC Redist compatible) with PDO_PGSQL and cURL extensions
- PostgreSQL 12 or greater
- Apache HTTP Server or IIS with URL Rewrite module

#### Step-by-step Installation

1. **Install PostgreSQL**
   
   - Download from [postgresql.org](https://www.postgresql.org/download/windows/)
   - Run the installer and follow the setup wizard
   - Remember the password for the `postgres` user
   - Note the port (default: 5432)

2. **Install PHP**
   
   - Download PHP 8.3 thread-safe ZIP from [php.net](https://windows.php.net/download/)
   - Extract to `C:\php`
   - Copy `php.ini-production` to `php.ini`
   - Enable required extensions in `php.ini`:
     - Uncomment `extension=pdo_pgsql`
     - Uncomment `extension=curl`
   - Add `C:\php` to your Windows PATH environment variable

3. **Install Apache**
   
   - Download from [Apache Lounge](https://www.apachelounge.com/download/)
   - Extract to `C:\Apache24` or use the installer
   - Install Apache as a service:
     
     ```cmd
     cd C:\Apache24\bin
     httpd.exe -k install
     ```

4. **Configure Apache for PHP**
   
   Edit `C:\Apache24\conf\httpd.conf` and add:
   
   ```apache
   LoadModule php_module "C:/php/php8.3-cgi.dll"
   AddType application/x-httpd-php .php
   PHPIniDir "C:/php"
   
   <IfModule mod_dir.c>
       DirectoryIndex index.html index.php
   </IfModule>
   ```

5. **Download and extract cyberx**
   
   - Download from [tirreno.com/download.php](https://www.tirreno.com/download.php)
   - Extract to `C:\Apache24\htdocs\cyberx`
   - Ensure proper permissions on the directory

6. **Create PostgreSQL database**
   
   Open Command Prompt or PowerShell and connect to PostgreSQL:
   
   ```cmd
   psql -U postgres -h localhost
   ```
   
   Run these commands:
   
   ```sql
   CREATE DATABASE cyberx;
   CREATE USER cyberx WITH PASSWORD 'your_secure_password';
   ALTER ROLE cyberx WITH CREATEDB;
   GRANT ALL PRIVILEGES ON DATABASE cyberx TO cyberx;
   \q
   ```

7. **Start Apache**
   
   ```cmd
   net start Apache2.4
   ```

   Or from Services: Right-click Apache2.4 → Start

8. **Run the installer**
   
   Open browser and navigate to `http://localhost/cyberx/install/index.php`

9. **Complete post-installation**
   
   Delete the `C:\Apache24\htdocs\cyberx\install\` directory

10. **Setup scheduled task for cron**
    
    Open Task Scheduler and create a new basic task:
    - Name: cyberx Cron
    - Trigger: Repeat every 10 minutes, indefinitely
    - Action: Start a program
    - Program: `C:\php\php.exe`
    - Arguments: `C:\Apache24\htdocs\cyberx\index.php /cron`

11. **Create administrator account**
    
    Navigate to `http://localhost/cyberx/signup/` and create an admin account.

## Quickstart install
1. [Download](https://www.tirreno.com/download.php) the latest version of cyberx (ZIP file).
2. Extract the cyberx-master.zip file to the location where you want it installed on your web server.
3. Navigate to `http://localhost:8585/install/index.php` in a browser to launch the installation process.
4. After the successful installation, delete the `install/` directory and its contents.
5. Navigate to `http://localhost:8585/signup/` in a browser to create an administrator account.
6. For cron job setup, insert the following schedule (every 10 minutes) expression with the `crontab -e` command or by editing the `/var/spool/cron/your-web-server` file:

```
*/10 * * * * /usr/bin/php /absolute/path/to/cyberx/index.php /cron
```

## Using Heroku (optional)

Click [here](https://heroku.com/deploy?template=https://github.com/cyberxtechnologies/cyberx) to launch heroku deployment.

## Via Composer and Packagist (optional)

cyberx is published at Packagist and could be installed with Composer:

```
composer create-project cyberx/cyberx
```

or could be pulled into an existing project:

```
composer require cyberx/cyberx
```

## SDKs

* [PHP](https://github.com/cyberxtechnologies/cyberx-php-tracker)
* [Python](https://github.com/cyberxtechnologies/cyberx-python-tracker)
* [NodeJS](https://github.com/cyberxtechnologies/cyberx-nodejs-tracker)

## Documentation

See the [User guide](https://docs.tirreno.com/) for details on how to use cyberx, [Developers documentation](https://github.com/cyberxtechnologies/DEVELOPMENT.md) to customize your integration, [Admin documentation](https://github.com/cyberxtechnologies/ADMIN.md) for installation, maintenance and updates.

## About

cyberx is an open-source security framework that embeds protection against threats, fraud, and abuse right into your product.

The project started as a proprietary system in 2021 and was open-sourced (AGPL) in December 2024.

Behind cyberx is a blend of extraordinary engineers and professionals, with over a decade of experience in cyberdefence. We solve real people's challenges through love in *ascétique* code and open technologies. cyberx is not VC-motivated. Our inspiration comes from the daily threats posed by organized cybercriminals, driving us to reimagine the place of security in modern applications.

## Why the name cyberx?

Tyrrhenian people may have lived in Tuscany and eastern Switzerland as far back as 800 BC. The term "Tyrrhenian" became more commonly associated with the Etruscans, and it is from them that the Tyrrhenian Sea derives its name, which is still in use today.

According to historical sources, Tyrrhenian people were the first to use trumpets for signaling about coming threats, which was later adopted by Greek and Roman military forces.

While working on the logo, we conducted our own historical study and traced mentions of 'cyberx' back to the 15th-century printed edition of the Vulgate (the Latin Bible). We kept it lowercase to stay true to the original — quite literally, by the book. The cyberx wordmark stands behind the horizon line, as a metaphor of the endless evolutionary cycle of the threat landscape and our commitment to rise over it.

## Links

* [Website](https://www.tirreno.com)
* [Live demo](https://play.tirreno.com)
* [Admin documentation](https://github.com/cyberxtechnologies/ADMIN.md)
* [Developers documentation](https://github.com/cyberxtechnologies/DEVELOPMENT.md)
* [User guide](https://docs.tirreno.com)
* [Mattermost community](https://chat.tirreno.com)

## Reporting a security issue

If you've found a security-related issue with cyberx, please email security@tirreno.com. Submitting the issue on GitHub exposes the vulnerability to the public, making it easy to exploit. We will publicly disclose the security issue after it has been resolved.

After receiving a report, cyberx will take the following steps:

* Confirm that the report has been received and is being addressed.
* Attempt to reproduce the problem and confirm the vulnerability.
* Release new versions of all the affected packages.
* Announce the problem prominently in the release notes.
* If requested, give credit to the reporter.

## License

This program is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License (AGPL) as published by the Free Software Foundation version 3.

The name "cyberx" is a registered trademark of cyberx technologies sàrl, and cyberx technologies sàrl hereby declines to grant a trademark license to "cyberx" pursuant to the GNU Affero General Public License version 3 Section 7(e), without a separate agreement with cyberx technologies sàrl.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License along with this program. If not, see [GNU Affero General Public License v3](https://www.gnu.org/licenses/agpl-3.0.txt).

## Authors

cyberx Copyright (C) 2026 cyberx technologies sàrl, Vaud, Switzerland. (License AGPLv3)

't'
