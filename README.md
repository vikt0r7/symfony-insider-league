# ⚽ Insider Champions League

A football league simulator built with Symfony and Vue.js. Inspired by the Premier League, it simulates weekly matches, shows standings, and allows predictions.

## 📦 Technologies

- **Symfony 6** — DDD, CQRS, Doctrine, Messenger
- **Vue 3 + Tailwind CSS** — frontend with fixtures, matches, and league table
- **Docker + Docker Compose** — isolated environment
- **Redis** — caching, Messenger transport, predictions
- **PHPUnit** — unit and functional testing

## ⚙️ Architecture

The project follows **Clean Architecture** and Domain-Driven Design (DDD):

- `Domain/` — domain entities and events
- `Application/` — commands, handlers, services
- `Service/` — business logic (simulation, predictions)
- `Controller/Api/` — thin API layer
- `DataFixtures/` — initial league data (teams, players)
- `frontend/` — Vue 3 SPA powered by Vite + Tailwind

## 🧰 Requirements

- Docker & Docker Compose
- Node.js (v18+ recommended)

## 🚀 Getting Started

### 1. Clone the repository

```bash
git clone https://github.com/your-username/symfony-insider-league.git
cd symfony-insider-league
```

### 2. Start Docker containers

```bash
docker-compose up -d --build
```

### 3. Install PHP dependencies

```bash
docker exec -it symfony-app bash
composer install
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction
```

### 4. Install and run frontend

```bash
cd frontend
npm install
npm run dev
```

### 5. Access the application

- 🖥 Frontend: http://localhost:5173
- 🔌 API: http://localhost:8080/api
- 📊 PgAdmin: http://localhost:8081
- 📬 Mailer (Mailpit): http://localhost:8025

---

## 🧪 Running Tests

Run unit and functional tests inside the PHP container:

```bash
docker exec -it symfony-app bash
php bin/phpunit
```

---

## 🛠 Usage

- Open the frontend to see the league table.
- Use Next Week to simulate matches week by week.
- After week 4, predictions will appear in the Predictions section.

---

## ✅ TODO

- Refactor controllers → service layer
- Switch to modular structure (Domain / Application / Infrastructure)
- Add club logos (black / white / fallback)
- Add more tests (unit + functional)
- Improve table styling (Premier League style)
- Add AI-based predictions (Groq / HuggingFace)
- Extract match simulation to dedicated service
- Setup CI/CD (Symfony + Vue via GitHub Actions)
- Enable ESLint, Prettier, and strict TS mode
- Add prediction editing & deletion
- Filter & paginate API responses

---

## 📷 Example UI

<img width="1245" alt="image" src="https://github.com/user-attachments/assets/9e73d87b-bcad-4bd2-9877-a1c415cad45e" />
