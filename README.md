# Chat App

A learning project exploring real-time chat features with Laravel, Vue.js, and Inertia.js.

## Local Development

- Docker [Laravel Sail](https://laravel.com/docs/12.x/sail)

## Tech Stack & Libraries

### Backend
- [Laravel](https://laravel.com/) - PHP framework
- [Inertia.js](https://inertiajs.com/) - SPA adapter
- [Laravel Broadcasting](https://laravel.com/docs/12.x/broadcasting) - Real-time events
- [Pusher](https://pusher.com/) - WebSocket service

### Frontend
- [Vue.js](https://vuejs.org/) - JavaScript framework
- [shadcn-vue](https://www.shadcn-vue.com/) - UI components
- [Tailwind CSS](https://tailwindcss.com/) - CSS framework
- [Lucide](https://lucide.dev/) - Icon library

## Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) installed and running
- [Git](https://git-scm.com/) for cloning the repository
- **WSL (Windows Subsystem for Linux)** - For Windows users, WSL is required for running Laravel Sail
- [Pusher Account](https://pusher.com/) - Sign up for free to get your API credentials

## Installation

### 1. Clone the Repository
```sh
git clone https://github.com/brixdesalit7/chat-app.git chat-app
cd chat-app
```

### 2. Install PHP Dependencies
```sh
composer install
```

### 3. Setup Configuration

**For Mac/Linux/WSL:**
```sh
cp .env.example .env
```

### 4. Configure Environment Variables

The `.env` file is already configured for Docker. The default database settings should work:
```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=chat-app
DB_USERNAME=sail
DB_PASSWORD=password
```

Update the `APP_URL` if needed:
```env
APP_URL=http://localhost:8000
APP_PORT=8000
```

**Configure Pusher credentials** (get these from your [Pusher dashboard](https://dashboard.pusher.com/)):
```env
BROADCAST_CONNECTION=pusher

PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=your_cluster
```

### 5. Start Docker Containers

**For Mac/Linux/WSL:**
```sh
./vendor/bin/sail up -d
```

> **Note**: The first run will take longer as Docker builds the containers.

### 6. Install Laravel Sail & Broadcasting

**For Mac/Linux/WSL:**
```sh
php artisan install:broadcasting --pusher
```

### 7. Generate Application Key

**For Mac/Linux/WSL:**
```sh
./vendor/bin/sail artisan key:generate
```

### 8. Run Database Migrations

**For Mac/Linux/WSL:**
```sh
./vendor/bin/sail artisan migrate
```

### 9. Install Frontend Dependencies
```sh
npm install
```

## Running the Application

### Start the Development Environment

Make sure Docker containers are running:

**For Mac/Linux/WSL:**
```sh
./vendor/bin/sail up -d
```

### Build Frontend Assets
```sh
npm run dev
```

### Access the Application

Open your browser and navigate to:
```
http://localhost:8000
```

