<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ApiProxyController extends Controller
{
    public function proxy(Request $request, $any = '')
    {
        $baseUrl = env('API_BASE_URL');
        if (!$baseUrl) {
            return response()->json(['error' => 'API_BASE_URL not configured'], 500);
        }

        $targetUrl = rtrim($baseUrl, '/') . '/' . ltrim($any, '/');
        if ($request->getQueryString()) {
            $targetUrl .= '?' . $request->getQueryString();
        }
        $response = Http::withHeaders($this->filterHeaders($request->headers->all()))
            ->send($request->method(), $targetUrl, [
                'body' => $request->getContent(),
                'timeout' => 30,
            ]);

        $laravelResponse = response($response->body(), $response->status());
        foreach ($response->headers() as $key => $values) {
            foreach ($values as $value) {
                $laravelResponse->header($key, $value);
            }
        }
        return $laravelResponse;
    }

    private function filterHeaders(array $headers): array
    {
        $hopByHop = [
            'host',
            'connection',
            'keep-alive',
            'proxy-authenticate',
            'proxy-authorization',
            'te',
            'trailers',
            'transfer-encoding',
            'upgrade',
        ];
        $filtered = [];
        foreach ($headers as $key => $values) {
            if (in_array(strtolower($key), $hopByHop)) {
                continue;
            }
            $filtered[$key] = is_array($values) && count($values) === 1 ? $values[0] : $values;
        }
        return $filtered;
    }
}
