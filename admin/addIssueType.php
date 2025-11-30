<?php
require_once '/xampp/htdocs/Municipality/backend/config/db.php';

$errormessage = "";
$successMessage = "";

// -------------------------
// UUID FUNCTION
// -------------------------
function uuidv4() {
    $data = random_bytes(16);
    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

// -------------------------
// FETCH ISSUE TYPES
// -------------------------
try {
    $sql = $pdo->query("
        SELECT id, issue_name
        FROM complaint_issues
        ORDER BY created_at DESC
    ");
    $issueTypes = $sql->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $issueTypes = [];
    echo "Error: " . $e->getMessage();
}

// -------------------------
// DELETE ISSUE TYPE
// -------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_issue_id'])) {

    $deleteId = $_POST['delete_issue_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM complaint_issues WHERE id = ?");
        $stmt->execute([$deleteId]);

        header("Location: /Municipality/admin.php?page=addIssueType");
        exit();
    } catch (PDOException $e) {
        die("Delete Error: " . $e->getMessage());
    }
}

// -------------------------
// ADD NEW ISSUE TYPE
// -------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {

    $name = trim($_POST['name']);

    // check duplicate
    $sql = $pdo->prepare("SELECT id FROM complaint_issues WHERE issue_name = ?");
    $sql->execute([$name]);
    $thisIssue = $sql->fetch(PDO::FETCH_ASSOC);

    if ($thisIssue) {
        $errormessage = "This issue type already exists";
    } else {
        $newId = uuidv4();
        $stmt = $pdo->prepare("
            INSERT INTO complaint_issues (id, issue_name)
            VALUES (?, ?)
        ");

        try {
            $stmt->execute([$newId, $name]);
            header("Location: /Municipality/admin.php?page=addIssueType");
            exit();
        } catch (PDOException $e) {
            die("Insert Error: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Issue Type</title>
    <link rel="stylesheet" href="/Municipality/css/addIssueType.css?v=4">
</head>

<body>
<div class="issueTypeModal_page_con">
    <div class="issueTypeModal_page">

        <!-- Header -->
        <div class="issueTypeModal_header">
            <h1>Add New Issue Type</h1>
        </div>

        <div class="issueTypeModal_body">

            <!-- Add Issue Type Form -->
            <div class="issueTypeModal_inpts">
                <label for="title">Issue Type Name</label>

                <form action="/Municipality/admin.php?page=addIssueType" method="post" class="issuetypeInpt">
                    <div>
                        <input
                            type="text"
                            placeholder="Enter issue type name"
                            id="title"
                            maxlength="50"
                            name="name"
                            required
                        />
                        <p class="error"><?= $errormessage ?></p>
                    </div>

                    <button>Add Issue Type</button>
                </form>
            </div>

            <!-- Existing Types List -->
            <div class="issueTypeModal_issues">
                <h4>Existing Issue Types</h4>

                <?php if (!empty($issueTypes)): ?>
                    <?php foreach ($issueTypes as $issueType): ?>
                    <div class="issueTypeModal_issue">
                        <p><?= htmlspecialchars($issueType['issue_name']) ?></p>

                        <form action="/Municipality/admin.php?page=addIssueType" method="post">
                            <input type="hidden" name="delete_issue_id" value="<?= $issueType['id'] ?>">
                            <button style="background-color: transparent; border:none " type="submit" class="deleteBtn">
                                <img class="issueTypeModal_trash_icon" src="/Municipality/images/trash-can.svg">
                            </button>
                        </form>
                    </div>
                    <?php endforeach ?>
                <?php else: ?>
                    <p>No issue types found.</p>
                <?php endif; ?>

            </div>

        </div>

    </div>
</div>
</body>
</html>
