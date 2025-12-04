# FlixGo – Movie Ticket Booking Website (PHP & MySQL)

A full-stack movie ticket booking web application built using **PHP** and **MySQL**, with a responsive frontend for users to browse movies, check showtimes, select seats, and complete bookings — delivering a smooth and user-friendly cinema booking experience.

---

## 🚀 Features

- Browse listed movies with details (title, synopsis, poster, showtimes)  
- Showtimes by date/time; user can select showtime and available seats  
- Seat selection UI — choose seats, view availability, booking confirmation  
- Booking flow with PHP backend: manages seat availability, bookings, user inputs  
- Responsive design — works well on desktop and mobile devices  
- Manageable backend structure: easy to extend (add movies, shows, seat layout)  

---

## 🛠️ Technology Stack

| Layer        | Technology        |
|--------------|-------------------|
| Backend      | PHP (core PHP)    |
| Database     | MySQL / MariaDB   |
| Frontend     | HTML, CSS, JavaScript (responsive layout + basic JS) |
| Server       | Any standard PHP-compatible web server (Apache, Nginx) |

---

## 📥 Installation / Setup (Local)

To get a local copy up and running, follow these steps:

```bash
# 1. Clone the repository
git clone https://github.com/Himesh565/FlixGo-Movie-Ticket-Booking-PHP-Website.git

# 2. Move into project directory
cd FlixGo-Movie-Ticket-Booking-PHP-Website

# 3. Set up database
#   - Create a MySQL database (e.g. `flixgo_db`)
#   - Import the included SQL file (if provided) or manually create tables as per schema

# 4. Configure database connection
#   - Edit the configuration file (e.g. `config.php` or similar) with your DB credentials

# 5. Run via local server
#   - If using PHP's built-in server:
php -S localhost:8000

#   - Or via Apache/Nginx — place project in server root / virtual host and access via browser

📂 Repository Structure
/ (root)
│  index.php            — main entry / homepage
│  movie_details.php    — movie detail & showtime page
│  booking.php          — seat selection & booking processing
│  config.php           — database configuration
│  assets/              — CSS, JS, images, posters, etc.
│  db/                  — (optional) SQL scripts for table setup
│  README.md            — project documentation


---

If you like — I can also **generate a README with badges + sample screenshots placeholder** (so it looks more modern and visually appealing).
::contentReference[oaicite:1]{index=1}
