# SocialMediaPost

This Laravel application allows you to post content to Twitter and LinkedIn.

## Installation

### Prerequisites
- PHP >= 8
- Composer installed globally

### Steps

1. Clone the repository:
   ```bash
   git clone https://github.com/your-username/SocialMediaPost.git

2. Navigate into the project directory:

```bash

 cd SocialMediaPost
```
2. Install composer dependencies:

```bash

 composer install
```
 ## Using Abraham\TwitterOAuth Package
### Installation
#### Install the package via Composer:

```bash

composer require abraham/twitteroauth
```
#### Setup 
1. Add Twitter API credentials (TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, TWITTER_ACCESS_TOKEN, TWITTER_ACCESS_TOKEN_SECRET) to your .env file:
```bash
TWITTER_CONSUMER_KEY=your_consumer_key
TWITTER_CONSUMER_SECRET=your_consumer_secret
TWITTER_ACCESS_TOKEN=your_access_token
TWITTER_ACCESS_TOKEN_SECRET=your_access_token_secret
Use the package in your Laravel application to post tweets.
```
2.Use the package in your Laravel application to post tweets.

## Using Laravel Socialite for LinkedIn
#### Installation
Install Laravel Socialite via Composer:


```bash
composer require laravel/socialite
```
#### Setup
Register your application and obtain the client_id and client_secret from LinkedIn Developer Portal.

Add LinkedIn API credentials (LINKEDIN_CLIENT_ID, LINKEDIN_CLIENT_SECRET, LINKEDIN_REDIRECT_URI) to your .env file.

Configure config/services.php to include LinkedIn:
```bash
'linkedin' => [
'client_id' => env('LINKEDIN_CLIENT_ID'),
'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
'redirect' => env('LINKEDIN_REDIRECT_URI'),
],
```
