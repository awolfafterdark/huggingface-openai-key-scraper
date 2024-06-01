<?php
include "./functions.php";

function check_key($key) {
    $ch = curl_init();
    $url = "https://openrouter.ai/api/v1/chat/completions";
    $headers = [
        "Content-Type: application/json",
        "Authorization: Bearer $key"
    ];
    
    // Prepare the request payload
    $payload = json_encode([
        'model' => 'google/gemma-7b-it:nitro',
        'messages' => [
            [
                'role' => 'system',
                'content' => "RETURN ONLY '0' AS YOUR ASSISTANT RESPONSE"
            ]
        ],
        'max_tokens' => 1,
        'stream' => false
    ]);
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 90);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    
    if ($response === false) {
        throw new Exception(curl_error($ch));
    }
    
    $response_data = json_decode($response, true);
    
    // Close the cURL session
    curl_close($ch);
    
    // Check if the response contains the expected 'choices' structure
    return isset($response_data['choices']) && count($response_data['choices']) > 0 && isset($response_data['choices'][0]['message']['content']);
}

$key = explode('--key=', $argv[1])[1];

if (check_key($key)) {
    echo "The key is valid.";
    // Further processing if needed
} else {
    echo "The key is invalid.";
}
?>
