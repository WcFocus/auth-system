<?php
class JWT {
    private static $secret_key = "clave_braveus"; // Cambiar a una clave segura

    public static function generateToken($data, $exp = 3600) {
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $payload = json_encode([
            'data' => $data,
            'exp' => time() + $exp
        ]);

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::$secret_key, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public static function validateToken($token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return false;

        list($header, $payload, $signature) = $parts;

        $expectedSignature = hash_hmac('sha256', $header . "." . $payload, self::$secret_key, true);
        $expectedSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($expectedSignature));

        if (!hash_equals($expectedSignature, $signature)) return false;

        $decodedPayload = json_decode(base64_decode($payload), true);
        if ($decodedPayload['exp'] < time()) return false; // Token expirado

        return $decodedPayload['data']; // Devuelve los datos del usuario autenticado
    }
}
?>
