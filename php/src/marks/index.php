<?php
include $_SERVER['DOCUMENT_ROOT'].'/header.php'; 
include $_SERVER['DOCUMENT_ROOT'].'/connect_db.php';
?>

<?php
  // if the delete button was clicked, delete the marks from the database and refresh the page
  if(array_key_exists('delete', $_POST)) {
    $sql = "DELETE FROM final_grades WHERE course_code = (SELECT course_code FROM marks WHERE id = ?) AND student_id = (SELECT student_id FROM marks WHERE id = ?);";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $_POST['delete'], $_POST['delete']);
    $stmt->execute();

    $sql = "DELETE FROM marks WHERE id = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_POST['delete']);
    $stmt->execute();
    $stmt->close();

    echo "<meta http-equiv='refresh' content='0;url=/marks'>";
  }
?>

<title>Marks</title>
<body class="page">
  <!-- Breadcumbs to navigate to previous page -->
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Marks</li>
    </ol>
  </nav>
  <h1>Marks <a class='btn btn-primary right' href='edit_marks.php'>Add Marks</a></h1>
  <div class="card">
    <!-- Marks Table -->
    <table class="table table-striped">
      <thead>
        <!-- Table head -->
        <tr>
          <th scope="col">Student ID</th>
          <th scope="col">Course Code</th>
          <th scope="col">Test 1</th>
          <th scope="col">Test 2</th>
          <th scope="col">Test 3</th>
          <th scope="col">Final Exam</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <!-- Table Body -->
        <?php
          // populate the table with data from the database
          $sql = "SELECT * FROM marks";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
            // If there are marks in the database, display them
            while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td>" . $row["student_id"] . "</td>
                      <td>" . $row["course_code"] . "</td>
                      <td>" . $row["test_1"] . "&#37</td>
                      <td>" . $row["test_2"] . "&#37</td>
                      <td>" . $row["test_3"] . "&#37</td>
                      <td>" . $row["final_exam"] . "&#37</td>
                      <td>
                        <form action='index.php' method='post'>
                          <a class='btn btn-warning btn-sm' href='edit_marks.php?mark_id=".$row["id"]."'>Edit</a>
                          <button class='btn btn-danger btn-sm' type='submit' name='delete' value='" . $row["id"] . "'>Delete</button>
                        </form>
                      </td>
                    </tr>";
            }
          } else {
            // if there are no marks in the database, display a message
            echo '<tr>
                    <td colspan="7">No data available in table</td>
                  </tr>';
          }
          $conn->close();
        ?>
      </tbody>
    </table>
  </div>
</body>

<?php include $_SERVER['DOCUMENT_ROOT'].'/footer.php'; ?>