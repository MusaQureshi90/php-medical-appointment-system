# 🏥 Clinical & Hospital Appointment Management System

A full-stack web application developed using **PHP**, **MySQL**, and **Bootstrap 5** designed to automate clinic workflows, medical specialist rosters, patient appointments, and administrative oversight across three secure, role-based workspaces.

---

## 🚀 Key Features & Multi-Role Workspaces

### 🧑‍⚕️ 1. Patient Portal
* **Account Registration & Authentication:** Secure patient account creation with encrypted credentials.
* **Specialist Discovery:** View medical specialist profiles, credentials, consultation days, and active time slots.
* **Online Booking:** Schedule appointments with real-time slot selection and reason/symptom logging.
* **Appointment Tracking:** Monitor live consultation status (`Pending`, `Completed`, `Cancelled`) and cancel upcoming appointments.

### 👨‍⚕️ 2. Doctor Portal
* **Doctor Workspace:** View categorized patient appointment schedules (Pending, Completed, Cancelled).
* **Clinical Records:** Document and persist patient notes (`appointment_notes.php`) for diagnosis history.
* **Availability Management:** Configure active consultation days and operating time slots dynamically.

### 🛡️ 3. Administrative Portal (Office Manager)
* **Central Analytics Dashboard:** Live metrics for total registered patients, doctors, and appointment counts.
* **Doctor & Patient CRUD:** Full administrative control to register, update profile details, or remove doctors and patient records.
* **Master Appointment Roster:** View all clinic appointments with administrative status overriding.

---

## 🛠️ Built With

* **Backend:** PHP 8.x
* **Database:** MySQL / MariaDB (Relational schema with Foreign Keys and ON DELETE CASCADE constraints)
* **Frontend:** Bootstrap 5, Animate.css, CSS3, JavaScript
* **Server Environment:** Apache HTTP Server (XAMPP / WAMP)

---

## 💻 How to Run Locally

### 1. Clone or Move Repository to Web Server
Place the project inside your XAMPP web server directory:
```bash
# Clone directly into htdocs
cd C:/xampp/htdocs
git clone https://github.com/MusaQureshi90/php-medical-appointment-system.git
```

### 2. Database Setup
1. Open **XAMPP Control Panel** and start **Apache** and **MySQL**.
2. Open **phpMyAdmin** in your browser: `http://localhost/phpmyadmin/`.
3. Create a new database named:
   ```sql
   medical-system
   ```
4. Click on the **Import** tab, select `appointments.sql` from the project directory, and click **Import**.

### 3. Verify Database Configuration
Ensure `db.php` reflects your local database credentials:
```php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "medical-system";
```

---

## 🔑 Access Portals & Credentials

| Role | Access URL | Default Credentials |
| :--- | :--- | :--- |
| **Patient Portal** | `http://localhost/medical-system/login.php` | Register via `register.php` |
| **Admin Portal** | `http://localhost/medical-system/admin_login.php` | **User:** `admin`<br>**Password:** `admin123` |
| **Doctor Portal** | `http://localhost/medical-system/doctor_login.php` | Added & configured via Admin Portal |
| **Admin Password Reset** | `http://localhost/medical-system/create_admin.php` | Hits script to restore default admin account |

---

## 👤 Author
**Muhammad Musa Qureshi**
* **GitHub:** [@MusaQureshi90](https://github.com/MusaQureshi90)
* **LinkedIn:** [Musa Qureshi](https://www.linkedin.com/in/musaqureshi90/)
