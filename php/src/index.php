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
            <!-- Four buttons to choose which table user wishes to access -->
            <button type="button" class="btn btn-primary" onclick="window.location.href='students'">Students</button>
            <button type="button" class="btn btn-primary" onclick="window.location.href='courses'">Courses</button>
            <button type="button" class="btn btn-primary" onclick="window.location.href='final_grades'">Final Grades</button>
            <button type="button" class="btn btn-primary" onclick="window.location.href='marks'">Marks</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
<?php include 'footer.php'; ?>
