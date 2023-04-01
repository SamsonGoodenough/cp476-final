<?php include 'header.php'; ?>
<title>Home</title>
<body class="page">
  <h1 class="text-center">Home</h1>

  <div class="container text-center ">
    <div class="row align-items-center">
      <div class="col">
        <div class="card text-center">
          <div class="card-header">
            CP476 - Database Management
          </div>
          <div class="card-body">
            <h5 class="card-title">Tables</h5>
            <!-- Four buttons to choose which table user wishes to access with small spaces -->
              <a type="button" class="btn btn-primary" href="students">Students</a>
              <a type="button" class="btn btn-primary" href="courses">Courses</a>
              <a type="button" class="btn btn-primary" href="marks">Marks</a>
              <a type="button" class="btn btn-primary" href="final_grades">Final Grades</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
<?php include 'footer.php'; ?>
