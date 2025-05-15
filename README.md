# Clinic Appointment Scheduling System

This Laravel-based web application is designed to manage clinic appointment scheduling for enrollment of new students. It aims to solve the long queues and inefficiencies of the traditional first-come, first-serve clinic system used during enrollment.

The system allows students to book appointments online, while clinic staff can monitor, approve, and manage daily appointment schedules efficiently. It supports role-based access for both admin and students, status-based appointment tracking, and includes backend scripts for maintenance and system health monitoring.

---

## ⚙️ System Requirements & Laravel Environment Setup

This part covers how to install **PHP, Composer,** and **Laravel** for both Linux and Windows environments.

### For Linux Environment

1. **Update the package list**

   ```bash
    sudo apt update
   ```

2. **Install PHP and necessary extensions**
   ```bash
   sudo apt install php php-cli php-mbstring php-xml php-bcmath php-curl php-mysql php-zip unzip curl git -y
   ```

3. **Install Composer globally**

   ```bash
    cd ~
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
   ```

4. **Check if composer is installed by checking its version**

   ```bash
    composer --version
   ```

5. **Install Laravel installer globally**

   ```bash
    composer global require laravel/installer
   ```

6. **Add Laravel to PATH**

   ```bash
    echo 'export PATH="$HOME/.composer/vendor/bin:$PATH"' >> ~/.bashrc
    source ~/.bashrc
   ```

### For Windows Environment

1. **Install PHP:**

   * Download PHP from [https://windows.php.net/download/](https://windows.php.net/download/) (Thread Safe version)
   * Extract to `C:\php` and add to your system PATH

2. **Enable PHP Extensions:**

   * Edit `php.ini` and uncomment the following:

     ```ini
     extension=curl
     extension=mbstring
     extension=xml
     extension=zip
     extension=openssl
     extension=mysqli
     extension=pdo_mysql
     extension=bcmath
     ```

3. **Install Composer:**

   * Download and install from [https://getcomposer.org/download/](https://getcomposer.org/download/)

4. **Install Laravel Installer:**

   ```bash
   composer global require laravel/installer
   ```

5. **Add Laravel to Path:**

   * Add `%USERPROFILE%\AppData\Roaming\Composer\vendor\bin` to the system PATH


## 🗄️ MySQL Setup & Project Configuration

### MySQL Setup 

1. **Log into MySQL**


```bash
sudo mysql -u root -p #or for windows, mysql -u root -p 
```

2. **Create database and user**
```bash
CREATE DATABASE clinic_db;
CREATE USER 'clinic_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON clinic_db.* TO 'clinic_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Clone & Setup Laravel Projects

Dont forget to install git
```bash
sudo apt install git -y
```

1. **Clone the repository**
   
```bash
cd https://github.com/pcsn99/Clinic-Appointment-System.git
```

2. **Install dependencies in both folders**

```bash
cd admin
composer install
cd ../student
composer install
```

### Environment Setup

1. **Copy the testing env to main .env (both projects)**
```bash

cp .env.testing .env
```
2. **Edit database credentials in the .env file**

```bash
DB_DATABASE=clinic_db
DB_USERNAME=clinic_user
DB_PASSWORD=your_secure_password
```

### Final Setup Steps

**In both admin and member folders**
```bash
php artisan key:generate
php artisan storage:link
php artisan migrate
```




## Default Admin Accounts

| Role      | Username               | Password     |
|-----------|------------------------|--------------|
| Clinic    | clinicadmin            | admin123  |


Use any of these to log in at `/`.



