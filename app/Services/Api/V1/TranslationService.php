<?php

namespace App\Services\Api\V1;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Exception;


class TranslationService
{
    protected string $apiKey;
    protected string $model;

    public function __construct()
    {
        $this->apiKey = config('services.openai.key');
        $this->model = env('OPENAI_MODEL', 'gpt-4o-mini');
    }

    public function translateBatch(array $texts, string $targetLanguage): array
    {
        $payload = json_encode($texts, JSON_UNESCAPED_UNICODE);

        /** @var Response $response */
        $response = Http::withToken($this->apiKey)
            ->timeout(20)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' =>
                        'Eres un traductor profesional. ' .
                            'Devuelve EXCLUSIVAMENTE un JSON válido, sin texto adicional, ' .
                            'sin bloques markdown y con las mismas claves.'
                    ],
                    [
                        'role' => 'user',
                        'content' =>
                        "Traduce el siguiente JSON al idioma {$targetLanguage}:\n{$payload}"
                    ],
                ],
                'temperature' => 0,
            ]);

        $content = $response->json('choices.0.message.content');

        $content = trim($content);
        $content = preg_replace('/^```json|```$/m', '', $content);

        $decoded = json_decode($content, true);

        if (!is_array($decoded)) {
            throw new Exception('Respuesta inválida del servicio de traducción');
        }

        return $decoded;
    }
}
