# Clinic Appointment Scheduling System

Welcome! Follow these steps to set up the **Laravel Project** locally.

---

## ⚙️ Step 1: Configure Environment File


1. Open `.env` and edit the **MySQL database credentials** to match your local setup:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=clinic_appointment
   DB_USERNAME=your_mysql_username
   DB_PASSWORD=your_mysql_password
   ```

2. Generate the application key:
   ```bash
   php artisan key:generate
   ```

---

##  Step 2: Navigate to the Admin Project Folder

In your terminal:

```bash
cd admin
```

Make sure you're in the correct Laravel folder before continuing.

---

## Step 3: Run Migrations and Seeders

Run the following commands:

```bash
php artisan migrate --seed
```

This will:
- Create all necessary tables
- Add default **admin accounts**

---

## Default Admin Accounts

| Role      | Username               | Password     |
|-----------|------------------------|--------------|
| Clinic    | admin@clinic           | password123  |
| Registrar | registrar@school       | password123  |
| PE        | pe@school              | password123  |

Use any of these to log in at `/`.

---

##  Need Help?

If anything breaks or the database is out of sync, you can reset it with:

```bash
php artisan migrate:fresh --seed
```
NOTE: THERE IS STILL NO DASHBOARD FILE. PLEASE ADD FRONT END HEHE

This will wipe and re-seed the database.



