<?php

use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

test('it posts test', function () {
    Http::fake();

    $service = app()->make(\Fuelviews\CloudflareCache\Services\CloudflareServiceInterface::class);
    $service->post('purge_cache');

    Http::assertSentCount(1);
    Http::assertSent(function (Request $request, Response $response) {
        $this->assertTrue($request->hasHeader('Authorization'));
        $this->assertTrue($request->hasHeader('Content-Type'));
        $this->assertSame($request->url(), 'https://api.cloudflare.com/client/v4/zones//purge_cache');

        expect($response)
            ->status()
            ->toBe(200);

        return $request;
    });
});
