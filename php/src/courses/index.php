<?php
include $_SERVER['DOCUMENT_ROOT'].'/header.php'; 
include $_SERVER['DOCUMENT_ROOT'].'/connect_db.php';
?>

<?php
  if(array_key_exists('delete', $_POST)) {
    $sql = "DELETE FROM courses WHERE code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_POST['delete']);
    $stmt->execute();
    $stmt->close();
    echo "<meta http-equiv='refresh' content='0;url=/courses'>";
  }
?>
<title>Courses</title>
<body class="page">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Courses</li>
    </ol>
  </nav>
  <h1>Courses <a class='btn btn-primary right' href='edit_course.php'>Add Course</a></h1>
  
  <div class="card">
    <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">Course Code</th>
          <th scope="col">Title</th>
          <th scope="col">Instructor</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
          // populate the table with data from the database
          $sql = "SELECT * FROM courses";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
            // If there are courses in the database, display them
            while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td>" . $row["code"] . "</td>
                      <td>" . $row["title"] . "</td>
                      <td>" . $row["instructor"] . "</td>
                      <td>
                        <form action='index.php' method='post'>
                          <a class='btn btn-warning btn-sm' href='edit_course.php?course_code=".$row["code"]."'>Edit</a>
                          <button class='btn btn-danger btn-sm' type='submit' name='delete' value='" . $row["code"] . "'>Delete</button>
                        </form>
                      </td>
                    </tr>";
            }
          } else {
            // if there are no courses in the database, display a message
            echo '<tr>
                    <td colspan="4">No data available in table</td>
                  </tr>';
          }
          $conn->close();
        ?>
      </tbody>
    </table>
  </div>
</body>

<?php include $_SERVER['DOCUMENT_ROOT'].'/footer.php'; ?>