<?php
$host = "localhost";
$user = "root"; // ✅ fixed
$password = "";
$dbname = "AHR";

// Database connection
$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// CREATE - Insert data
if (isset($_POST['save'])) {
    $name = $_POST['name'];
    $department = $_POST['department'];

    $conn->query("INSERT INTO student(Name, Department) VALUES('$name', '$department')");
    header("location: index.php");
    exit;
}

// UPDATE - Update data
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $department = $_POST['department'];

    $conn->query("UPDATE student SET Name='$name', Department='$department' WHERE ID=$id");
    header("location: index.php");
    exit;
}

// DELETE - Delete data
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM student WHERE ID=$id");
    header("location: index.php");
    exit;
}

// EDIT - Fetch single row for update
$editRow = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM student WHERE ID=$id");
    $editRow = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Example</title>
</head>
<body>
    <h1>Database Presentation</h1>
    <table border="1" cellpadding="5">
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Department</th>
            <th>Action</th>
        </tr>
        <?php
        $data = $conn->query("SELECT * FROM student");
        while ($row = $data->fetch_assoc()) {
            echo "
            <tr>
                <td>{$row['ID']}</td>
                <td>{$row['Name']}</td>
                <td>{$row['Department']}</td>
                <td>
                    <a href='index.php?edit={$row['ID']}'>Edit</a> | 
                    <a href='index.php?delete={$row['ID']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                </td>
            </tr>";
        }
        ?>
    </table>

    <!-- Creation Form -->
    <h2>Add Student Information</h2>
    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" required>
        <label>Department:</label>
        <input type="text" name="department" required>
        <button type="submit" name="save">Save Info</button>
    </form>

    <!-- Update Form -->
    <?php if ($editRow): ?>
    <h2>Update Student</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $editRow['ID'] ?>">
        <label>Name:</label>
        <input type="text" name="name" value="<?= $editRow['Name'] ?>" required>
        <label>Department:</label>
        <input type="text" name="department" value="<?= $editRow['Department'] ?>" required>
        <button type="submit" name="update">Update</button>
    </form>
    <?php endif; ?>
</body>
</html>