# Social Networking Site for Gamers

Welcome to **Social Networking Site for Gamers**!  
This project is an online platform designed to connect gamers, foster communities, and make it easy to share achievements, discover new games, and interact with fellow enthusiasts.

## 🚀 Features

- User registration and authentication
- Create and customize gamer profiles
- Add friends and join gaming communities
- Share posts, screenshots, and achievements
- Game discovery and recommendations
- Event organization and participation

## 🛠️ Tech Stack

**Backend:**
- PHP ^8.2
- Laravel Framework ^12.0
- Laravel Tinker

**Frontend:**
- Vite (build tool)
- Tailwind CSS (including @tailwindcss/forms, @tailwindcss/vite)
- Alpine.js
- PostCSS
- Axios (HTTP requests)
- Laravel Vite Plugin
- Popper.js (tooltips/popovers)

**Other:**
- Concurrently (run multiple processes at once)
- Autoprefixer (CSS prefixing)

**Database:**
- SQL (relational database support, e.g., MySQL/PostgreSQL via Laravel migrations)

> For more details, see the [package.json](https://github.com/DziedzicFilip/SocialNetworkingSiteForGamers/blob/main/SocialNetworkingSiteForGamers/package.json) and [composer.json](https://github.com/DziedzicFilip/SocialNetworkingSiteForGamers/blob/main/SocialNetworkingSiteForGamers/composer.json).

## 💾 Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/DziedzicFilip/SocialNetworkingSiteForGamers.git
   cd SocialNetworkingSiteForGamers
   ```

2. **Install dependencies:**
   ```bash
   # Backend (Laravel)
   cd SocialNetworkingSiteForGamers
   composer install

   # Frontend
   npm install
   ```

3. **Configure environment variables:**  
   Copy `.env.example` to `.env` and adjust the settings as needed.

4. **Set up your local environment (XAMPP):**
   - Make sure XAMPP is installed and running (Apache and MySQL).
   - Create a database for the project using phpMyAdmin or the MySQL CLI.
   - Update your `.env` file with your local database credentials (DB_DATABASE, DB_USERNAME, DB_PASSWORD).
   - Run migrations to set up the database schema:
     ```bash
     php artisan migrate
     ```

5. **Run the application:**
   ```bash
   # Start the Laravel backend server
   php artisan serve

   # In a separate terminal, start the frontend/Vite dev server
   npm run dev
   ```

6. Visit `http://localhost:8000` (or your configured port) in your browser.

## 📸 Screenshots

<!-- Add images/screenshots here if available -->
<!-- ![Screenshot 1](screenshots/homepage.png) -->

## 📚 Usage

1. Register a new account or sign in.
2. Set up your gamer profile.
3. Start adding friends, joining communities, and sharing your gaming moments!

