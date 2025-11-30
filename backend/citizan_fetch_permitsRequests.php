<?php  
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '/xampp/htdocs/Municipality/backend/config/db.php';  
$userId = $_SESSION['user_id'];

$conditions = ["pr.user_id = :userId"];
$params = ["userId" => $userId];

/* ---------------------
   1. Search by title/category
---------------------- */
if (!empty($_GET['search'])) {
    $conditions[] = "(p.title LIKE :search OR c.name LIKE :search)";
    $params['search'] = "%" . $_GET['search'] . "%";
}

/* ---------------------
   2. Status filter
---------------------- */
if (!empty($_GET['status']) && $_GET['status'] !== "all") {
    $conditions[] = "pr.status = :status";
    $params['status'] = $_GET['status'];
}

/* ---------------------
   3. Sorting
---------------------- */
$orderBy = "pr.requested_at DESC"; // default newest

if (!empty($_GET['sort']) && $_GET['sort'] === "oldest") {
    $orderBy = "pr.requested_at ASC";
}

try {
    $query = "
        SELECT 
            pr.id,
            p.title AS permit_title,
            c.name AS category,
            pr.status,
            pr.requested_at,
            pr.completed_at
        FROM permits_requests pr
        LEFT JOIN permits p ON pr.permit_id = p.id
        LEFT JOIN permits_categories c ON p.category_id = c.id
        WHERE " . implode(" AND ", $conditions) . "
        ORDER BY $orderBy
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error loading permit requests: " . $e->getMessage());
}
?>