<?php
include $_SERVER['DOCUMENT_ROOT'].'/header.php'; 
include $_SERVER['DOCUMENT_ROOT'].'/connect_db.php';
?>

<title>Final Grades</title>
<body class="page">
  <!-- Breadcumbs to navigate to previous page -->
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Final Grades</li>
    </ol>
  </nav>
  <h1>Final Grades</h1>
  <div class="card">
    <!-- Marks Table -->
    <table class="table table-striped">
      <thead>
        <!-- Table head -->
        <tr>
          <th scope="col">Student ID</th>
          <th scope="col">Student Name</th>
          <th scope="col">Course Code</th>
          <th scope="col">Final Grade</th>
        </tr>
      </thead>
      <tbody>
        <!-- Table Body -->
        <?php
          // populate the table with data from the database
          $sql = "SELECT * FROM final_grades";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
            // If there are marks in the database, display them
            while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td>" . $row["student_id"] . "</td>
                      <td>" . $row["student_name"] . "</td>
                      <td>" . $row["course_code"] . "</td>
                      <td>" . $row["grade"] . "&#37</td>
                    </tr>";
            }
          } else {
            // if there are no marks in the database, display a message
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