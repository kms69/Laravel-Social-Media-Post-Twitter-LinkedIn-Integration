<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LinkedInController extends Controller
{
    public function post(Request $request): \Illuminate\Http\JsonResponse
    {
        $message = $request->input('message');

        // Replace with your actual LinkedIn access token and LinkedIn member ID
        $accessToken = 'your_access_token';
        $linkedinMemberId = 'your_company_id'; // Replace with your LinkedIn member ID

        $body = [
            'author' => 'urn:li:member:' . $linkedinMemberId, // Corrected 'memeber' to 'member'
            'lifecycleState' => 'PUBLISHED',
            'specificContent' => [
                'com.linkedin.ugc.ShareContent' => [
                    'shareCommentary' => ['text' => $message],
                    'shareMediaCategory' => 'NONE',
                ],
            ],
            'visibility' => ['com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC'],
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'X-Restli-Protocol-Version' => '2.0.0',
                'Content-Type' => 'application/json',
            ])->post('https://api.linkedin.com/v2/ugcPosts', $body);

            if ($response->failed()) {
                $errorDetails = $response->json();
                Log::error('LinkedIn API request failed', [
                    'request_body' => $body,
                    'response' => $errorDetails,
                    'status' => $response->status(),
                ]);
                return response()->json([
                    'error' => 'LinkedIn API request failed',
                    'details' => $errorDetails
                ], $response->status());
            }

            return response()->json($response->json(), $response->status());

        } catch (\Exception $e) {
            Log::error('LinkedIn API request error', [
                'message' => $e->getMessage(),
                'request_body' => $body,
            ]);
            return response()->json(['error' => 'LinkedIn API request error'], 500);
        }
    }


    public function callback(Request $request): \Illuminate\Http\JsonResponse
    {
        $code = $request->input('code');

        if (!$code) {
            return response()->json(['error' => 'No authorization code found'], 400);
        }

        try {
            $response = Http::asForm()->post('https://www.linkedin.com/oauth/v2/accessToken', [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => 'callback_url',
                'client_id' => 'client_id',
                'client_secret' => 'client_secret',
            ]);

            if ($response->successful()) {
                $accessToken = $response->json()['access_token'];
                Cache::put('linkedin_access_token', $accessToken, 60 * 60);
                return response()->json(['access_token' => $accessToken]);
            } else {
                $errorDetails = $response->json();
                Log::error('LinkedIn Authorization Error', [
                    'response' => $errorDetails,
                    'status' => $response->status(),
                ]);
                return response()->json([
                    'error' => 'LinkedIn Authorization Error',
                    'details' => $errorDetails
                ], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('LinkedIn Authorization Error', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'LinkedIn Authorization Error'], 500);
        }
    }
}
