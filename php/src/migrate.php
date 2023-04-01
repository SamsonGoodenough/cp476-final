<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Migration</title>
</head>
<body>
  <form action="migrate.php" method="post">
    <input type="submit" name="submit" value="Run Migration">
  </form>

  <?php
    if (isset($_POST['submit'])) {
      // if the form has been submitted, run the migration
      include __DIR__ . '/Helper/DotEnv.php';
      (new DotEnv(__DIR__ . '/.env'))->load();

      $host = 'db';
      $user = 'root';
      $pass = getenv('MYSQL_ROOT_PASSWORD');

      // check the MySQL connection status
      $conn = new mysqli($host, $user, $pass);

      $sql = "CREATE DATABASE IF NOT EXISTS menagerie;
    USE menagerie;
    
    CREATE TABLE
      `students` (
        `id` int NOT NULL,
        `name` varchar(255) NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
      ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;
     
    CREATE TABLE
      `marks` (
        `id` int unsigned NOT NULL AUTO_INCREMENT,
        `student_id` int NOT NULL,
        `course_code` varchar(10) DEFAULT NULL,
        `test_1` float DEFAULT NULL,
        `test_2` float DEFAULT NULL,
        `test_3` float DEFAULT NULL,
        `final_exam` float DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
      ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;
      
    CREATE TABLE
      `final_grades` (
        `id` int unsigned NOT NULL AUTO_INCREMENT,
        `student_id` int NOT NULL,
        `student_name` varchar(255) NOT NULL,
        `course_code` varchar(10) NOT NULL,
        `grade` float NOT NULL COMMENT 'Final grade (test 1,2,3-3x20%, final exam 40%)',
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
      ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;
      
    CREATE TABLE
      `courses` (
        `code` varchar(10) NOT NULL COMMENT 'e.g. CP476',
        `title` varchar(255) NOT NULL COMMENT 'e.g. Internet Computing',
        `instructor` varchar(255) DEFAULT NULL COMMENT 'e.g. Lunshan Gao',
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`code`)
      ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;";
      
      if ($conn->multi_query($sql) === TRUE) {
        echo "Tables created successfully";
      } else {
        echo "Error creating tables: " . $conn->error;
      }
    }
  ?>
</body>
</html>