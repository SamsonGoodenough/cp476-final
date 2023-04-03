
<?php
  include $_SERVER['DOCUMENT_ROOT'].'/header.php'; 
  include $_SERVER['DOCUMENT_ROOT'].'/connect_db.php';

  if (array_key_exists('course_code', $_GET)) {
    // if the course code is in the URL, we are editing the course
    // get the course code from the URL
    $course_code = strtoupper($_GET['course_code']);
    // get the course data from the database
    $sql = "SELECT * FROM courses WHERE code = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $course_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $course_title = $row['title'];
      $course_instructor = $row['instructor'];
    } else {
      echo "Error: Course not found.";
    }
  } else { 
    // if the course code is not in the URL, we are adding a new course
    $course_code = '';
    $course_title = '';
    $course_instructor = '';
  }

  if (array_key_exists('save', $_POST)) {
    // if the form was submitted, save the data
    // get the form data
    $course_code = $_POST['course_code'];
    $course_title = $_POST['course_title'];
    $course_instructor = $_POST['course_instructor'];

    // sanitize the data
    $course_code = preg_replace(sanitize, '', $course_code);
    $course_title = preg_replace(sanitize, '', $course_title);
    $course_instructor = preg_replace(sanitize, '', $course_instructor);

    // save the data to the database while protecting against SQL injection
    $sql = "INSERT INTO courses (code, title, instructor) 
            VALUES (UPPER(?), ?, ?) 
            ON DUPLICATE KEY UPDATE title = ?, instructor = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $course_code, $course_title, $course_instructor, $course_title, $course_instructor);
    $stmt->execute();
    $stmt->close();
    // redirect back to the courses page
    echo "<meta http-equiv='refresh' content='0;url=/courses'>";
  }
?>

<title>Manage Course</title>
<body class="page">
  <!-- Breadcumbs to navigate to previous pages -->
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="/courses">Courses</a></li>
      <li class="breadcrumb-item active" aria-current="page">Manage Course</li>
    </ol>
  </nav>
  <!-- Form Container -->
  <div class="container col-4">
    <div class="row align-items-center">
      <div class="col">
        <div class="card">
          <div class="card-title">
            <h2 class="text-center"><?php print($course_code != "" ? "Edit":"Add") ?> Course</h2>
          </div>
          <div class="card-body">
            <!-- Add / Edit Course Form -->
            <form class="needs-validation" action="edit_course.php" method="post" autocomplete="off" novalidate>
              <div class="form-group mb-3">
                <label for="course_code">Course Code</label><br>
                <input class="form-control <?php print($course_code != '' ? 'disabled':'')?>" type="text" name="course_code" oninput='validateCourse(this)' value="<?php echo $course_code; ?>" <?php print($course_code != '' ? 'readonly':'')?> required>
              </div>
              <div class="form-group mb-3">
                <label for="course_name">Course Name</label><br>
                <input class="form-control" type="text" name="course_title" value="<?php echo $course_title; ?>" required>
              </div>
              <div class="form-group mb-3">
                <label for="course_instructor">Course Instructor</label><br>
                <input class="form-control" type="text" name="course_instructor" value="<?php echo $course_instructor; ?>" required>
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


<script>
  (function () {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
      .forEach(function (form) {
        form.addEventListener('submit', function (event) {
          if (!validateCourse(form.elements['course_code']) || !validateInput(form.elements['course_title']) || !validateInput(form.elements['course_instructor'])) {
            event.preventDefault()
            event.stopPropagation()
          }

          form.classList.add('was-validated')
        }, false)
      })
  })()


  function validateCourse(course) {
    console.log(course.value);
    const regex = /[a-zA-Z]+\d+/gm
    if (course.value == '') {
      // set custom validity message
      course.setCustomValidity('Please select a course');
      return false;
    } else if (course.value.match(regex) == null) {
      // set custom validity message
      course.setCustomValidity('Course is not in the correct format');
      return false; 
    }
    else {
      course.setCustomValidity('');
      return true;
    }
  }
  function validateInput(input) {
    if (input.value == '') {
      // set custom validity message
      input.setCustomValidity('Please enter a value');
      return false;
    } else {
      input.setCustomValidity('');
      return true;
    }
  }
</script>

<?php include $_SERVER['DOCUMENT_ROOT'].'/footer.php'; ?>
