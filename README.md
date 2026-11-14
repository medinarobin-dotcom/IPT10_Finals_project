# Threadline Clothing Store (built with CorePHP)

An online clothing store made with my own PHP framework, **CorePHP**.

You need: **XAMPP** (for PHP and MySQL) and **Visual Studio Code**.

---

## Setup (do this once)

**1. Extract the project**

- Put `CorePHP.zip` in `C:\xampp\htdocs`
- Right-click it, choose **Extract All**, then click **Extract**
- You should now have `C:\xampp\htdocs\CorePHP` with the folders `app`, `core`, `public`, ... inside

**2. Start MySQL**

- Open the **XAMPP Control Panel**
- Click **Start** next to **MySQL** (and **Apache**, which phpMyAdmin needs)

**3. Create the database**

- Go to <http://localhost/phpmyadmin>
- Click **Import**, then **Choose File**
- Pick `C:\xampp\htdocs\CorePHP\database\clothing_store.sql`
- Click **Import** at the bottom
- A database named **corephp** appears on the left

---

## Run the store

**1.** In VS Code, click **File > Open Folder** and open `C:\xampp\htdocs\CorePHP`

**2.** Click **Terminal > New Terminal** and run:

```
php -S localhost:8000 -t public
```

**3.** Open <http://localhost:8000> in your browser.

Leave the terminal open while you use the store. Press **Ctrl + C** to stop it.

> The address is already set to `http://localhost:8000` in `config/app.php`,
> so the design and pictures load right away. Nothing to change.

---

## Demo accounts

| Who      | Email             | Password    |
|----------|-------------------|-------------|
| Admin    | admin@example.com | admin123    |
| Customer | juan@example.com  | password123 |

---

## Run the tests

In the VS Code terminal (in the CorePHP folder), run:

```
php tests/run.php
```

You should see `13 passed, 0 failed`.

---

## If something goes wrong

| Problem | Fix |
|---|---|
| `Directory public does not exist` | Your terminal is in the wrong folder. Run `cd C:\xampp\htdocs\CorePHP` first. |
| `Unknown database 'corephp'` | Do Setup step 3 again (import the .sql file). |
| `Connection refused` / database error | MySQL is not started. Click **Start** in XAMPP. |
| Page looks unstyled | Make sure you opened **http://localhost:8000** (not another address). Press **Ctrl + F5**. |
| `Address already in use` | The store is already running in another terminal. Use that one, or close it. |

---

## What is inside

```
CorePHP/
├── core/          the CorePHP framework (Router, Request, Response, Database)
├── config/        app.php - settings (address, database login)
├── public/        index.php (start point), CSS and product images
├── routes/        web.php - list of all pages
├── app/
│   ├── Controllers/   page logic (shop, cart, login, orders, admin)
│   ├── Models/        database queries (products, users, orders)
│   ├── Services/      cart + currency converter (Frankfurter API)
│   ├── Middleware/    login check, admin check, request logging
│   └── Views/         the HTML pages
├── database/      clothing_store.sql - tables + demo data
└── tests/         run.php - unit tests
```
