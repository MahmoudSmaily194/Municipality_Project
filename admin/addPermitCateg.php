<?php 
require_once '/xampp/htdocs/Municipality/backend/config/db.php';

$errormessage = "";

/* ---------------------
   Shortened UUID v4
---------------------- */
function uuidv4() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        random_int(0, 0xffff), random_int(0, 0xffff),
        random_int(0, 0xffff),
        random_int(0, 0x0fff) | 0x4000,
        random_int(0, 0x3fff) | 0x8000,
        random_int(0, 0xffff), random_int(0, 0xffff), random_int(0, 0xffff)
    );
}

/* ------------------------------
   DELETE CATEGORY
------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category_id'])) {
    $deleteId = $_POST['delete_category_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM permits_categories WHERE id = ?");
        $stmt->execute([$deleteId]);

        header("Location: /Municipality/admin.php?page=addPermitCateg");
        exit();
    } catch (PDOException $e) {
        die("Delete Error: " . $e->getMessage());
    }
}

/* ------------------------------
   ADD NEW CATEGORY
------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {

    $name = trim($_POST['name']);

    if ($name === "") {
        $errormessage = "Category name cannot be empty";
    } else {

        // Check if category exists
        $sql = $pdo->prepare("SELECT id FROM permits_categories WHERE name = ?");
        $sql->execute([$name]);
        $exists = $sql->fetch(PDO::FETCH_ASSOC);

        if ($exists !== false) {
            $errormessage = "This category already exists";
        } else {

            try {
                $stmt = $pdo->prepare("
                    INSERT INTO permits_categories (id, name)
                    VALUES (?, ?)
                ");
                $stmt->execute([uuidv4(), $name]);

                header("Location: /Municipality/admin.php?page=addPermitCateg");
                exit();

            } catch (PDOException $e) {
                die("Insert Error: " . $e->getMessage());
            }
        }
    }
}

/* ------------------------------
   FETCH CATEGORIES LIST
------------------------------- */
try {
    $result = $pdo->query("
        SELECT id, name
        FROM permits_categories 
        ORDER BY created_at DESC
    ");
    $permitsCateg = $result->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $permitsCateg = [];
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Permit Category</title>
    <link rel="stylesheet" href="/Municipality/css/addPermitCateg.css?v=3">
</head>
<body>

<div class="serviceCategory_page_con">
  <div class="serviceCategory_page">

    <div class="serviceCategory_header">
      <h1>Add Permit Category</h1>
    </div>

    <div class="serviceCategory_body">

      <!-- ADD CATEGORY -->
      <div class="serviceCategory_inpts">
        <label for="title">Category Name</label>

        <form action="/Municipality/admin.php?page=addPermitCateg" method="post" class="serviceCategoryInpt">
          <div>
            <input
              type="text"
              placeholder="Enter category name"
              id="title"
              name="name"
              required
              maxlength="50"
            />
            <p class="error"><?= $errormessage ?></p>
          </div>

          <button type="submit">Add</button>
        </form>
      </div>

      <!-- CATEGORY LIST -->
      <div class="serviceCategory_issues">
        <h4>Existing Categories</h4>

        <?php if (!empty($permitsCateg)): ?>
            <?php foreach ($permitsCateg as $category): ?>

            <div class="serviceCategory_issue">
                <p><?= htmlspecialchars($category['name']) ?></p>

                <!-- Delete Form -->
                <form action="/Municipality/admin.php?page=addPermitCateg" method="post">
                    <input type="hidden" name="delete_category_id" value="<?= $category['id'] ?>">
                    <button style="background-color: transparent; border:none " type="submit" class="deleteBtn">
                        <img class="serviceCategory_trash_icon" src="/Municipality/images/trash-can.svg">
                    </button>
                </form>
            </div>

            <?php endforeach ?>
        <?php else: ?>
            <p>No categories found.</p>
        <?php endif; ?>

      </div>

    </div>

  </div>
</div>

</body>
</html>
