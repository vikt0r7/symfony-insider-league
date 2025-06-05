# ⚽ Insider Champions League

A football league simulator on Symfony + Vue.js. The project implements the mechanics of a mini-league in the style of the Premier League with predictions, matches by week and a final table.

## 📦 Technologies

- Symfony 6 (DDD, CQRS, Doctrine, Messenger)
- Vue 3 + Tailwind CSS (table, matches, predictions)
- Docker + Docker Compose
- Redis (cache, predictions, Messenger transport)
- PHPUnit (unit and functional tests)

## ⚙️ Architecture

The project follows Clean Architecture + DDD + CQRS:

## Example image
<img width="1245" alt="image" src="https://github.com/user-attachments/assets/9e73d87b-bcad-4bd2-9877-a1c415cad45e" />

## ✅ TODO

- Refactor controllers → service layer
- Switch to modular structure (Domain / App / Infra)
- Add club logos (black / white / default)
- Add more tests (unit + functional)
- Improve table styling (Premier League style)
- Add AI-based predictions (Groq / HuggingFace)
- Extract match simulation to MatchSimulator service
- Setup CI/CD (Symfony + Vue via GitHub Actions)
- Enable ESLint, Prettier, strict TS mode
- Add prediction editing & deleting
- Filter & paginate API responses
- Add more tests (unit + functional)

