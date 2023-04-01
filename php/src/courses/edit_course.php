<?php
include $_SERVER['DOCUMENT_ROOT'].'/header.php'; 
include $_SERVER['DOCUMENT_ROOT'].'/connect_db.php';

if (array_key_exists('course_code', $_GET)) { // edit existing course
  $course_code = $_GET['course_code'];
  // get the course data from the database
  $sql = "SELECT * FROM courses WHERE code = '$course_code'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $course_title = $row['title'];
    $course_instructor = $row['instructor'];
  } else {
    echo "Error: Course not found.";
  }
} else { // add new course
  $course_code = '';
  $course_title = '';
  $course_instructor = '';
}

// if the form was submitted, save the data
if (array_key_exists('save', $_POST)) {
  
  echo $course_code;
  $course_code = $_POST['course_code'];
  $course_title = $_POST['course_title'];
  $course_instructor = $_POST['course_instructor'];

  $sql = "INSERT INTO courses (code, title, instructor) 
          VALUES (?, ?, ?) 
          ON DUPLICATE KEY UPDATE title = ?, instructor = ?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssss", $course_code, $course_title, $course_instructor, $course_title, $course_instructor);
  $stmt->execute();
  $stmt->close();
  // redirect to the courses page
  echo "<meta http-equiv='refresh' content='0;url=/courses'>";
}
?>
<title>Manage Course</title>
<body class="page">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="/courses">Courses</a></li>
      <li class="breadcrumb-item active" aria-current="page">Manage Course</li>
    </ol>
  </nav>

  <div class="container col-4">
    <div class="row align-items-center">
      <div class="col">
        <div class="card">
          <div class="card-title">
            <h2 class="text-center"><?php print($course_code != "" ? "Edit":"Add") ?> Course</h2>
          </div>
          <div class="card-body">
            <form action="edit_course.php" method="post" autocomplete="off">
              <div class="form-group mb-3">
                <label for="course_code">Course Code</label><br>
                <input class="form-control <?php print($course_code != '' ? 'disabled':'')?>" type="text" name="course_code" value="<?php echo $course_code; ?>" <?php print($course_code != '' ? 'readonly':'')?>>
              </div>
              <div class="form-group mb-3">
                <label for="course_name">Course Name</label><br>
                <input class="form-control" type="text" name="course_title" value="<?php echo $course_title; ?>">
              </div>
              <div class="form-group mb-3">
                <label for="course_instructor">Course Instructor</label><br>
                <input class="form-control" type="text" name="course_instructor" value="<?php echo $course_instructor; ?>">
              </div>
              <div class="d-grid">
                <button class="btn btn-primary" type="submit" name="save">Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

<?php include $_SERVER['DOCUMENT_ROOT'].'/footer.php'; ?>
