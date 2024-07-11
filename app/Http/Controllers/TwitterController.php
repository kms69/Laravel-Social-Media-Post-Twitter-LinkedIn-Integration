<?php

namespace App\Http\Controllers;

use Abraham\TwitterOAuth\TwitterOAuth;
use Abraham\TwitterOAuth\TwitterOAuthException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TwitterController extends Controller
{
    /**
     * @throws TwitterOAuthException
     */
    public function postTweet(Request $request): \Illuminate\Http\JsonResponse
    {
        $status = $request->input('status');

        if (!$status) {
            return response()->json(['error' => 'Status message is required.'], 400);
        }

        $consumerKey = env('TWITTER_CONSUMER_KEY');
        $consumerSecret = env('TWITTER_CONSUMER_SECRET');
        $accessToken = env('TWITTER_ACCESS_TOKEN');
        $accessTokenSecret = env('TWITTER_ACCESS_TOKEN_SECRET');

        if (!$consumerKey || !$consumerSecret || !$accessToken || !$accessTokenSecret) {
            Log::error('Twitter API keys or tokens are not set properly.');
            return response()->json(['error' => 'Twitter API keys or tokens are not set properly.'], 401);
        }

        $connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
        $connection->setApiVersion('2');

        Log::info('Attempting to post tweet', ['status' => $status]);

        $response = $connection->post('tweets', ['text' => $status], ['jsonPayload' => true]);

        $httpCode = $connection->getLastHttpCode();
        Log::info('Twitter response', ['response' => $response, 'http_code' => $httpCode]);

        if (in_array($httpCode, [200, 201])) {
            Log::info('Tweet posted successfully');
            return response()->json(['message' => 'Tweet posted successfully!']);
        } elseif ($httpCode == 401) {
            Log::error('Unauthorized - Check your API keys and tokens.', ['response' => $response]);
            return response()->json(['error' => 'Unauthorized - Check your API keys and tokens.'], 401);
        } elseif ($httpCode == 404) {
            Log::error('Not Found - Check your endpoint.', ['response' => $response]);
            return response()->json(['error' => 'Not Found - Check your endpoint.'], 404);
        } else {
            Log::error('Error posting tweet', ['response' => $response]);
            return response()->json(['error' => 'Error posting tweet.'], $httpCode);
        }
    }
}
