<?php
header('Content-Type: application/json');
require_once '/xampp/htdocs/Municipality/backend/config/db.php';

/* ---------------------------------
   1. Validate complaint ID
--------------------------------- */
$complaintId = $_GET['id'] ?? null;
if (!$complaintId) {
    echo json_encode(['success' => false, 'message' => 'Invalid complaint ID']);
    exit();
}

/* ---------------------------------
   2. Fetch complaint data
--------------------------------- */
$stmt = $pdo->prepare("
    SELECT
        c.*,
        ci.issue_name,
        CONCAT(u.first_name, ' ', u.last_name) AS reporter
    FROM complaints c
    LEFT JOIN complaint_issues ci ON c.issue_id = ci.id
    LEFT JOIN users u ON c.created_by = u.id
    WHERE c.id = ?
");
$stmt->execute([$complaintId]);
$complaint = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$complaint) {
    echo json_encode(['success' => false, 'message' => 'Complaint not found']);
    exit();
}

/* ---------------------------------
   3. Build AI prompt
--------------------------------- */
$prompt = <<<PROMPT
You are an AI assistant for a Smart Municipality system.

Respond ONLY with valid JSON.
Do not add any text outside JSON.

Complaint:
Description: "{$complaint['description']}"
Issue Type: "{$complaint['issue_name']}"
Current Status: "{$complaint['status']}"

JSON FORMAT:
{
  "confidence": "0-100%",
  "issue_match": "Yes or No",
  "suggested_status": "pending | in_progress | completed | rejected",
  "suggested_importance": "low | medium | high",
  "reason": "short explanation"
}
PROMPT;

/* ---------------------------------
   4. Prepare Ollama request
--------------------------------- */
$requestData = [
    'model' => 'llama3.2',
    'prompt' => $prompt,
    'stream' => false,
];

/* ---------------------------------
   5. Send request to Ollama
--------------------------------- */
$ch = curl_init('http://localhost:11434/api/generate');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode($requestData),
    CURLOPT_TIMEOUT => 60,
]);

$response = curl_exec($ch);
curl_close($ch);

if ($response === false) {
    echo json_encode(['success' => false, 'message' => 'Failed to connect to AI']);
    exit();
}

/* ---------------------------------
   6. Parse Ollama response
--------------------------------- */
$ollamaResponse = json_decode($response, true);
$aiText = $ollamaResponse['response'] ?? '';
$aiAdvice = json_decode($aiText, true);

/* ---------------------------------
   7. Fallback if AI fails
--------------------------------- */
if (!is_array($aiAdvice)) {
    $aiAdvice = [
        'confidence' => '0%',
        'issue_match' => 'Unknown',
        'suggested_status' => $complaint['status'],
        'suggested_importance' => 'medium',
        'reason' => 'AI analysis failed',
    ];
}

/* ---------------------------------
   8. Return everything as JSON
--------------------------------- */
echo json_encode([
    'success' => true,
    'complaint' => $complaint,
    'aiAdvice' => $aiAdvice
]);
?>