# Event Registration Demo (Symfony)

A didactic showcase project built with Symfony 7 to demonstrate my backend engineering skills to recruiters. It collects registrations for a company family event and highlights solid engineering practices: clean architecture, security, i18n, async processing, and developer tooling.

## Overview (What this project demonstrates)
- Event registration flow with form validation and persistence
- Optional confirmation by email via signed URL
- Department capture per registration to support departmental managers
- Secure form handling and hardened HTTP security headers
- Internationalization-ready (code in English, translations structure ready)
- Async background processing with Symfony Messenger
- Production-like local environment via Docker/DDEV

## Tech Stack & Tools
- Language/Runtime: PHP >= 8.2
- Framework: Symfony 7.3 (FrameworkBundle, Form, Validator, Security, Messenger, Mailer, Twig, Translation, HTTP Client, Console, Asset/Mapper, WebLink)
- Persistence: Doctrine ORM 3.x, Doctrine Migrations, Doctrine DBAL 3
- Queue/Async: Symfony Messenger (Doctrine transport in local dev)
- Templates/UI: Twig
- Security: symfony/security-bundle, Nelmio Security Bundle (XSS/Clickjacking protections, headers)
- Testing/Quality: PHPUnit 12, PHP-CS-Fixer, Rector, PHPStan/PHPDoc parser
- Observability/Dev: Monolog, Web Profiler, Debug bundle
- Dev Env: Docker + DDEV

## Key Features
- Register attendees for a company family event (employee + plus one + kids)
- Capture department and preferences (to enable department-level manager oversight)
- Send confirmation email with unique link (optional extra)
- Admin-friendly defaults and clear validation messages

## Architecture at a Glance
- Controllers (HTTP adapters) in src/Controller
- Forms and validation in src/Form + Symfony Validator
- Domain entities and repositories in src/Entity and src/Repository (Doctrine)
- Async tasks via Messenger (src/Message, src/MessageHandler)
- Templating with Twig (templates/*) and translations (translations/*)

## Data Model (simplified)
- Registration: stores registrant details, department, preferences, email, and confirmation status/timestamp.

## Live (Local) Demo URLs
- App: https://app.ddev.site
- Mailer (Mailpit UI): https://app.ddev.site:8026/

## Getting Started (Local)
1. Prerequisites
   - Docker
   - DDEV (Symfony quickstart)
2. Bootstrap
   - Copy .env to .env.local and set local overrides (see below)
   - Start: `ddev start`
   - Launch browser: `ddev launch`
   - Consume async messages (in a separate terminal):
     `ddev exec -s web php bin/console messenger:consume async -vv`
   - Stop: `ddev stop`
3. Database migrations
   ```shell
   ddev php bin/console doctrine:migrations:migrate -n
   ```
4. Useful endpoints
   - App: https://app.ddev.site
   - Mailer inbox: https://app.ddev.site:8026/

## Environment (.env.local examples)
```
MAIL_FROM_ADDRESS=no-reply@app.ddev.site
MAILER_DSN="smtp://127.0.0.1:1025"
MESSENGER_TRANSPORT_DSN="doctrine://default"
APP_URL="https://app.ddev.site"
```

## Developer Experience
- Code style (PHP-CS-Fixer):
  ```shell
  ddev exec -s web ./vendor/bin/php-cs-fixer fix src
  ```
- Automated refactoring (Rector):
  ```shell
  ddev exec -s web ./vendor/bin/rector process
  ```
- Clear cache:
  ```shell
  ddev php bin/console cache:clear
  ```

## Database (local defaults)
- host: 127.0.0.1
- port: 33066
- user: root
- password: root

## Security & Best Practices Highlight
- Uses Symfony Security Bundle for CSRF protection on forms
- NelmioSecurityBundle for secure headers and XSS protections
- Validation on all inputs using Symfony Validator
- Environment-specific configs and secrets; sensitive values not committed

## Internationalization (i18n)
- Codebase in English, translations directory prepared for additional locales
- Twig templates structured for easy message extraction and translation

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change. Please update/add tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)
