# Laravel 11 JSON Web Token (JWT) API Authentication

This repository provides a simple implementation of JSON Web Token (JWT) authentication for APIs using Laravel 11. It includes user registration, login, and protected routes.

## Table of Contents
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
  - [User Registration](#user-registration)
  - [User Login](#user-login)
  - [Protected Routes](#protected-routes)
- [Testing the API](#testing-the-api)
- [License](#license)

## Features
- User registration with validation
- User login with JWT generation
- Middleware for protecting routes
- Error handling for invalid credentials and other issues

## Requirements
- PHP >= 8.0
- Laravel 11.x
- Composer
- Database (MySQL, SQLite, etc.)

## Installation

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/yourusername/laravel-jwt-auth.git
   cd laravel-jwt-auth
