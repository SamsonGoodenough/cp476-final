
<?php
  include $_SERVER['DOCUMENT_ROOT'].'/header.php'; 
  include $_SERVER['DOCUMENT_ROOT'].'/connect_db.php';

  if (array_key_exists('student_id', $_GET)) { 
    // if the student id is in the URL, we are editing the student
    // get the student id from the URL
    $student_id = $_GET['student_id'];
    // get the student data from the database
    $sql = "SELECT * FROM students WHERE id = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $student_name = $row['name'];
    } else {
      echo "Error: Student not found.";
    }
  } else { 
    // if the student id is not in the URL, we are adding a new student
    $student_id = '';
    $student_name = '';
  }

  if (array_key_exists('save', $_POST)) {
    // if the form was submitted, save the data
    // get the form data
    $student_id = intval($_POST['student_id']);
    $student_name = $_POST['student_name'];

    // sanitize the data
    $student_id = preg_replace(sanitize, '', $student_id);
    $student_name = preg_replace(sanitize, '', $student_name);

    // save the data to the database while protecting against SQL injection
    $sql = "INSERT INTO students (id, `name`, deleted_at) 
            VALUES (?, ?, NULL) 
            ON DUPLICATE KEY UPDATE name = ?, deleted_at = NULL;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $student_id, $student_name, $student_name);
    $stmt->execute();
    $stmt->close();
    // redirect back to the students page
    echo "<meta http-equiv='refresh' content='0;url=/students'>";
  }
?>

<title>Manage Student</title>
<body class="page">
  <!-- Breadcumbs to navigate to previous pages -->
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="/students">Students</a></li>
      <li class="breadcrumb-item active" aria-current="page">Manage Student</li>
    </ol>
  </nav>
  <!-- Form Container -->
  <div class="container col-4">
    <div class="row align-items-center">
      <div class="col">
        <div class="card">
          <div class="card-title">
            <h2 class="text-center"><?php print($student_id != "" ? "Edit":"Add") ?> Student</h2>
          </div>
          <div class="card-body">
            <!-- Add / Edit student Form -->
            <form class="needs-validation" action="edit_student.php" method="post" autocomplete="off" novalidate>
              <div class="form-group mb-3">
                <label for="student_id">Student ID</label><br>
                <input class="form-control <?php print($student_id != '' ? 'disabled':'')?>" type="text" name="student_id" value="<?php echo $student_id; ?>" <?php print($student_id != '' ? 'readonly':'')?> oninput='validateInput(this)' required>
              </div>
              <div class="form-group mb-3">
                <label for="student_name">Name</label><br>
                <input class="form-control" type="text" name="student_name" value="<?php echo $student_name; ?>" oninput='validateInput(this)' required>
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
          if (!validateInput(form.elements['student_id']) || !validateInput(form.elements['student_name'])) {
            event.preventDefault()
            event.stopPropagation()
          }

          form.classList.add('was-validated')
        }, false)
      })
  })()


  function validateInput(input) {
    console.log(input);
    if (input.value == '') {
      // set custom validity message
      input.setCustomValidity('Please enter a value');
      return false;
    } else if (input.name == 'student_id' && input.value != '' && !/^\d+$/.test(input.value)) {
      // set custom validity message
      input.setCustomValidity('Please enter a number');
      return false;
    } else if(input.name == 'student_name' && input.value != '' && input.value.length > 255) {
        // set custom validity message
        input.setCustomValidity('Name is too long');
        return false;
    } else {
      // clear custom validity message
      input.setCustomValidity('');
      return true;
    }
  }
</script>

<?php include $_SERVER['DOCUMENT_ROOT'].'/footer.php'; ?>
