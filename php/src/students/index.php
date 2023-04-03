<?php
include $_SERVER['DOCUMENT_ROOT'].'/header.php'; 
include $_SERVER['DOCUMENT_ROOT'].'/connect_db.php';
?>

<?php
  // if the delete button was clicked, soft delete the student from the database and refresh the page
  if(array_key_exists('delete', $_POST)) {
    $date = date("Y-m-d H:i:s");
    $sql = "UPDATE students SET deleted_at = ? WHERE id = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $date, $_POST['delete']);
    $stmt->execute();
    $stmt->close();
    echo "<meta http-equiv='refresh' content='0;url=/students'>";
  }
?>

<title>Students</title>
<body class="page">
  <!-- Breadcumbs to navigate to previous page -->
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Students</li>
    </ol>
  </nav>
  <h1>Students <a class='btn btn-primary right' href='edit_student.php'>Add Student</a></h1>
  <div class="card">
    <!-- Students Table -->
    <table class="table table-striped">
      <thead>
        <!-- Table head -->
        <tr>
          <th scope="col">Student ID</th>
          <th scope="col">Full Name</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <!-- Table Body -->
        <?php
          // populate the table with data from the database
          $sql = "SELECT * FROM students WHERE deleted_at IS NULL";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
            // If there are students in the database, display them
            while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td>" . $row["id"] . "</td>
                      <td>" . $row["name"] . "</td>
                      <td>
                        <form action='index.php' method='post'>
                          <a class='btn btn-warning btn-sm' href='edit_student.php?student_id=".$row["id"]."'>Edit</a>
                          <button class='btn btn-danger btn-sm' type='submit' name='delete' value='" . $row["id"] . "'>Delete</button>
                        </form>
                      </td>
                    </tr>";
            }
          } else {
            // if there are no students in the database, display a message
            echo '<tr>
                    <td colspan="3">No data available in table</td>
                  </tr>';
          }
          $conn->close();
        ?>
      </tbody>
    </table>
  </div>
</body>

<?php include $_SERVER['DOCUMENT_ROOT'].'/footer.php'; ?>