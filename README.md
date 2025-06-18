# 🧾 Travel Quotation

A Laravel 10 REST API that calculates travel insurance quotations based on traveler age, trip duration, and currency.  
Includes Sanctum token-based authentication, a Bootstrap frontend form to generate and copy tokens, and a smooth form submission UX.

---

## ⚙️ Setup Instructions

### 1. Clone and Install
```bash
git clone https://github.com/marslankhalid/travel-quotation.git
cd travel-quotation
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Configure `.env`
Set up your database configuration:

```env
DB_DATABASE=quotation_db
DB_USERNAME=root
DB_PASSWORD=secret
```

### 3. Run Migrations and Seeder
```bash
php artisan migrate
php artisan db:seed
```

This creates a demo user and sets up your tables.

### 4. Serve the Application
```bash
php artisan serve
```

---

## 🔐 Authentication (Sanctum)

Sanctum is used to authenticate users via API tokens.  
A token must be generated before accessing protected endpoints.

### Generate Token via UI
- Go to: [http://localhost:8000/form](http://localhost:8000/form)
- Enter demo user credentials
- Click **"Generate Token"**
- The form will:
    - Display the token
    - Enable the quotation form
    - Hide the login form

### Demo Credentials
| Field    | Value              |
|----------|--------------------|
| Email    | demo@example.com   |
| Password | password           |

---

## 🧪 API Endpoint

### POST `/api/quotation`

#### Headers
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer <token>
```

#### Request Body
```json
{
  "age": "28,35",
  "currency_id": "EUR",
  "start_date": "2020-10-01",
  "end_date": "2020-10-30"
}
```

#### Response
```json
{
  "quotation_id": 1234,
  "currency_id": "EUR",
  "total": 117.00
}
```

---

## 📊 Calculation Logic

```
Total = Sum of (3 * age_load * trip_days)
```

### Age Load Table

| Age Range | Load |
|-----------|------|
| 18–30     | 0.6  |
| 31–40     | 0.7  |
| 41–50     | 0.8  |
| 51–60     | 0.9  |
| 61–70     | 1.0  |

Trip length is inclusive of start and end dates.

---

## 🖥️ Frontend Token + Quotation Form

Visit:
```
http://localhost:8000/form
```

### Features
- Token login form (hidden after success)
- Token display + copy-to-clipboard
- Quotation form unlocked after token
- Success/error messages
- Smooth scroll to form

---

## 🛠 Artisan Commands Summary

```bash
php artisan migrate             # Run migrations
php artisan db:seed             # Seed demo user
php artisan serve               # Run local dev server
php artisan test:token          # (Optional) Generate token via CLI
```

---

## 👨‍💻 Author

**Muhammad Arslan Khalid**  
🔗 [LinkedIn](https://www.linkedin.com/in/helloarslan)
