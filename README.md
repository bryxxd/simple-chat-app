# Chat App

A real-time chat application built with Laravel, Vue.js, and Inertia.js.

## Local Development

- Docker [Larave Sail](https://laravel.com/docs/12.x/sail)

## Tech Stack & Libraries

### Backend
- [Laravel](https://laravel.com/) - PHP framework
- [Inertia.js](https://inertiajs.com/) - SPA adapter

### Frontend
- [Vue.js](https://vuejs.org/) - JavaScript framework
- [shadcn-vue](https://www.shadcn-vue.com/) - UI components
- [Tailwind CSS](https://tailwindcss.com/) - CSS framework
- [Lucide](https://lucide.dev/) - Icon library

## Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) installed and running
- [Git](https://git-scm.com/) for cloning the repository

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

**For Mac/Linux:**
```sh
cp .env.example .env
```

**For Windows (Command Prompt):**
```cmd
copy .env.example .env
```

**For Windows (PowerShell/Git Bash):**
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

### 5. Start Docker Containers

**For Mac/Linux:**
```sh
./vendor/bin/sail up -d
```

**For Windows:**
```cmd
.\vendor\bin\sail up -d
```

> **Note**: The first run will take longer as Docker builds the containers.

### 6. Generate Application Key

**For Mac/Linux:**
```sh
./vendor/bin/sail artisan key:generate
```

**For Windows:**
```cmd
.\vendor\bin\sail artisan key:generate
```

### 7. Run Database Migrations

**For Mac/Linux:**
```sh
./vendor/bin/sail artisan migrate
```

**For Windows:**
```cmd
.\vendor\bin\sail artisan migrate
```

### 8. Install  Dependencies

```sh
npm install
```

```sh
composer install
```

## Running the Application

### Start the Development Environment

Make sure Docker containers are running:

**For Mac/Linux:**
```sh
./vendor/bin/sail up -d
```

**For Windows:**
```cmd
.\vendor\bin\sail up -d
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