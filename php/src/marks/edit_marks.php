
<?php
  include $_SERVER['DOCUMENT_ROOT'].'/header.php'; 
  include $_SERVER['DOCUMENT_ROOT'].'/connect_db.php';

  if (array_key_exists('mark_id', $_GET)) {
    $readonly = true;
    // if the course code is in the URL, we are editing the course
    // get the course code from the URL
    $mark_id = $_GET['mark_id'];
    // get the course data from the database
    $sql = "SELECT * FROM marks WHERE id = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $mark_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $student_id = $row['student_id'];
      $course_code = $row['course_code'];
      $test_1 = $row['test_1'];
      $test_2 = $row['test_2'];
      $test_3 = $row['test_3'];
      $final_exam = $row['final_exam'];
    } else {
      echo "Error: marks not found.";
    }
  } else { 
    // if the mark id is not in the URL, we are adding a new course
    $mark_id = null;
    $student_id = null;
    $course_code = '';
    $test_1 = null;
    $test_2 = null;
    $test_3 = null;
    $final_exam = null;
  }

  if (array_key_exists('save', $_POST)) {
    // if the form was submitted, save the data
    // get the form data
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $test_1 = round($_POST['test_1'],1);
    $test_2 = round($_POST['test_2'],1);
    $test_3 = round($_POST['test_3'],1);
    $final_exam = round($_POST['final_exam'],1);

    
    // save the data to the database while protecting against SQL injection

    $sql = "INSERT INTO marks (student_id, course_code, test_1, test_2, test_3, final_exam) 
            VALUES (?, ?, ?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE student_id = ?, course_code = ?, test_1 = ?, test_2 = ?, test_3 = ?, final_exam = ?;";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isddddisdddd", $student_id, $course_id, $test_1, $test_2, $test_3, $final_exam, $student_id, $course_id, $test_1, $test_2, $test_3, $final_exam);
    $stmt->execute();

    // save the final grade to the database while protecting against SQL injection
    $final_grade = $test_1 * 0.2 + $test_2 * 0.2 + $test_3 * 0.2 + $final_exam * 0.4;
    $sql = "INSERT INTO final_grades (student_id, student_name, course_code, grade)
            VALUES (?, (SELECT name FROM students WHERE id = ?), ?, ?)
            ON DUPLICATE KEY UPDATE student_name = (SELECT name FROM students WHERE id = ?), course_code = ?, grade = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisdisd", $student_id, $student_id, $course_id, $final_grade, $student_id, $course_id, $final_grade);
    $stmt->execute();
    $stmt->close();
    // redirect back to the courses page
    echo "<meta http-equiv='refresh' content='0;url=/marks'>";
  }
?>

<title>Manage Marks</title>
<body class="page">
  <!-- Breadcumbs to navigate to previous pages -->
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="/marks">Marks</a></li>
      <li class="breadcrumb-item active" aria-current="page">Manage Grade</li>
    </ol>
  </nav>
  <!-- Form Container -->
  <div class="container col-4">
    <div class="row align-items-center">
      <div class="col">
        <div class="card">
          <div class="card-title">
            <h2 class="text-center"><?php print($mark_id != null ? "Edit":"Add") ?> Grade</h2>
          </div>
          <div class="card-body">
            <!-- Add / Edit Course Form -->
            <form class="needs-validation" action="edit_marks.php" method="post" autocomplete="off" novalidate>

              <div class="form-group mb-3">
                <select class="form-select" aria-label="" name="student_id" oninput="validateStudent(this)" required>
                  <option <?php print($student_id != '' ? 'selected':'')?> value=''>Select Student</option>
                  <?php
                    $sql = "SELECT * FROM students WHERE deleted_at IS NULL;";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                      while($row = $result->fetch_assoc()) {
                        if($row['id'] != $student_id){
                          echo "<option value=" . $row['id'] . ">" . $row['id'] . " - " . $row["name"] . "</option>";
                        } else {
                          echo "<option selected value=" . $row['id'] . ">" . $row['id'] . " - " . $row["name"] . "</option>";
                        }
                      }
                    }
                  ?>
                </select>
              </div>
              <div class="form-group mb-3">
                <select class="form-select" aria-label="" name="course_id" required>
                  <option <?php print($course_code != '' ? 'selected':'')?> value=''>Select Course</option>
                      <?php
                        $sql = "SELECT * FROM courses;";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                          while($row = $result->fetch_assoc()) {
                            if($row['code'] != $course_code){
                              echo "<option value=" . $row['code'] . ">" . $row['code'] . " - " . $row["title"] . "</option>";
                            } else {
                              echo "<option selected value=" . $row['code'] . ">" . $row['code'] . " - " . $row["title"] . "</option>";
                            }
                          }
                        }
                      ?>
                </select>
              </div>
              <div class="form-group mb-3">         
                <div class="row">
                  <div class="col-3">
                    <label for="test_1">Test 1</label>
                    <input type="number" step="0.0001" class="form-control" id="test_1" name="test_1" value="<?php print($test_1) ?>" oninput="validateGrades(this)" required>
                  </div>
                  
                  <div class="col-3">
                    <label for="test_2">Test 2</label>
                    <input type="number" step="0.0001" class="form-control" id="test_2" name="test_2" value="<?php print($test_2) ?>" oninput="validateGrades(this)" required>
                  </div>

                  <div class="col-3">
                    <label for="test_3">Test 3</label>
                    <input type="number" step="0.0001" class="form-control" id="test_3" name="test_3" value="<?php print($test_3) ?>" oninput="validateGrades(this)" required>
                  </div>

                  <div class="col-3">
                    <label for="final_exam">Final Exam</label>
                    <input type="number" step="0.0001" class="form-control" id="final_exam" name="final_exam" value="<?php print($final_exam) ?>" oninput="validateGrades(this)" required>
                    
                  </div>
                </div>
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
          if (!validateStudent(form.elements['student_id']) || !validateCourse(form.elements['course_id']) || !validateGrades(form.elements['test_1']) || !validateGrades(form.elements['test_2']) || !validateGrades(form.elements['test_3']) || !validateGrades(form.elements['final_exam'])) {
            event.preventDefault()
            event.stopPropagation()
          }

          form.classList.add('was-validated')
        }, false)
      })
  })()

  function validateStudent(dropdown) {
    var selectedValue = dropdown.options[dropdown.selectedIndex].value;
    if (selectedValue == '') {
      // set custom validity message
      dropdown.setCustomValidity('Please select a student');
      return false;
    } else {
      dropdown.setCustomValidity('');
      return true;
    }
  }
  function validateCourse(dropdown) {
    var selectedValue = dropdown.options[dropdown.selectedIndex].value;
    if (selectedValue == '') {
      // set custom validity message
      dropdown.setCustomValidity('Please select a course');
      return false;
    } else {
      dropdown.setCustomValidity('');
      return true;
    }
  }
  function validateGrades(input) {
    // remove letters from input
    // ensure grade is a float and has only numbers excluding "." using regex
    console.log(input.value, typeof input.value, /\e/gm.test(input.value));
    if (input.value == '') {
      input.setCustomValidity('Grade cannot be empty');
      return false;
    } else if (/\e/gm.test(input.value)) {
      input.setCustomValidity('Grade must be a number');
      return false;
    }
    else if (input.value < 0 || input.value > 100) {
      input.setCustomValidity('Grade must be between 0 and 100');
      return false;
    } else {
      input.setCustomValidity('');
      return true;
    }
  }
</script>

<?php include $_SERVER['DOCUMENT_ROOT'].'/footer.php'; ?>
