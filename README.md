# Basic Chat API

Laravel API for a basic chat application

## Installation

### Local Development

Default port is set to 97, you can change it in the .env file

For the sake of fast deployment app .env file is included in the repository

```sh
  cp .env.example .env # Can be skipped if you are ok with default ports
  docker compose up -d
  docker exec basic-chat-php php artisan key:generate
  docker exec basic-chat-php php artisan migrate
```
