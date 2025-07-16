# Chat App

A real-time chat application built with Laravel, Vue.js, and Inertia.js.

## Requirements

- PHP 8.1+
- Composer
- Node.js & NPM
- MySQL database

## Tech Stack & Libraries

### Backend
- [Laravel](https://laravel.com/) - PHP framework
- [Inertia.js](https://inertiajs.com/) - SPA adapter

### Frontend
- [Vue.js](https://vuejs.org/) - JavaScript framework
- [shadcn-vue](https://www.shadcn-vue.com/) - UI components
- [Tailwind CSS](https://tailwindcss.com/) - CSS framework
- [Lucide](https://lucide.dev/) - Icon library

## Installation

Clone the repo locally:

```sh
git clone https://github.com/brixdesalit7/chat-app.git chat-app
cd chat-app
```

Install PHP dependencies:

```sh
composer install
```

Install NPM dependencies:

```sh
npm install
```

Setup configuration:

```sh
cp .env.example .env
```

Generate application key:

```sh
php artisan key:generate
```

**Create a database** and update your `.env` file with database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=chat_app
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Run database migrations:

```sh
php artisan migrate
```

## Running the Application

Start the Laravel development server (the output will give the address):

```sh
php artisan serve
```

In a separate terminal, start the frontend development server:

```sh
npm run dev
```