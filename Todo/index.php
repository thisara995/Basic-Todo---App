<?php
session_start();
require_once './include/connection.php';

// Fetch todos from the database
$todos = [];
$query = $conn->prepare("SELECT * FROM todos");
$query->execute();
$todos = $query->get_result()->fetch_all(MYSQLI_ASSOC);

// Initialize data for the form
$data = ['todo' => '', 'id' => ''];

// Check if there is an update request
if (isset($_GET['update_id'])) {
    $id = $_GET['update_id'];
    $query = $conn->prepare("SELECT * FROM todos WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
    } else {
        echo "<script>alert('Todo not found');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A Basic Todo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-body">
                <!-- Form for adding/updating todos -->
                <form method="post" action="./controller/todo.php" autocomplete="off">
                    <div class="mb-3 text-center">
                    <?php include('./include/message.php'); ?>
                        <h3>To Do App</h3>
                        <input type="text" class="form-control d-inline-block w-50" name="todo" value="<?php echo htmlspecialchars($data['todo']); ?>" required placeholder="Create new todo">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['id']); ?>">
                        <input class="btn btn-success ms-2" type="submit" value="Submit">
                    </div>
                </form>
                
                <!-- Table displaying todos -->
                <table class="table table-bordered mt-3" style="width: 80%; margin: auto;">
                    <thead class="table-success">
                        <tr>
                            <th>ID</th>
                            <th>Todo</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($todos)) { ?>
                        <?php foreach ($todos as $todo) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($todo['id']); ?></td>
                                <td><?php echo htmlspecialchars($todo['todo']); ?></td>
                                <td>
                                    <?php if ($todo['status']) : ?>
                                        <span class="badge bg-success">Completed</span>
                                    <?php else : ?>
                                        <span class="badge bg-danger">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($todo['created']); ?></td>
                                <td><?php echo htmlspecialchars($todo['updated']); ?></td>
                                <td>
                                    <a href="./controller/todo.php?delete_id=<?php echo $todo['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                    <a href="?update_id=<?php echo $todo['id']; ?>" class="btn btn-warning btn-sm">Update</a>
                                    <?php if (!$todo['status']) { ?>
                                        <a href="./controller/todo.php?marking_id=<?php echo $todo['id']; ?>" class="btn btn-success btn-sm">Mark Complete</a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="6" class="text-center">No todos found.</td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
