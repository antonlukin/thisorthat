# This or That

API and Web application for Russian version of This or That game.

## Installation
1. Download repository from GitHub: `git clone https://github.com/antonlukin/thisorthat.git`
2. Create `.env` file from `.env.example`
3. Install required packages with `composer update` and `yarn`
4. Use `pm2 start image/app.js --name avatars` to start avatar service
5. Set cron tasks for `cron/` scripts

## Development
1. Use `yarn build` to build web app
2. Update `docs/files/index.md` and rebuild html with `yarn docs`

## Requirements
1. NodeJS 14.0+
2. PHP 7.0+ with Redis and MySQL extensions
3. Redis server 4.0+
4. Cron service for moderation handlers
5. MySQL 5.6+
6. Yarn and Composer to build application
7. pm2 for starting avatar service