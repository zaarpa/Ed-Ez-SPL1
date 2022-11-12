<?php
$root_path = '../../../';
$profile_path = '../';
require $root_path . 'LibraryFiles/DatabaseConnection/config.php';
require $root_path . 'LibraryFiles/URLFinder/URLPath.php';
require $root_path . 'LibraryFiles/SessionStore/session.php';
require $root_path . 'LibraryFiles/Utility/Utility.php';
require $root_path . 'LibraryFiles/ValidationPhp/InputValidation.php';
session::profile_not_set($root_path);
$tableName = $_SESSION['tableName'];
$email = new EmailValidator($_SESSION['email']);
$validate = new InputValidation();
$classCode = $_SESSION['class_code'];
$task =
  $classrooms = $database->performQuery("SELECT * FROM classroom,student_classroom where classroom.class_code=student_classroom.class_code and student_classroom.email='" . $email->get_email() . "' and active='1';");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Classroom</title>
  <link rel="icon" href="<?php echo $root_path; ?>title_icon.jpg" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css" />
  <script defer src="script.js"></script>
  <link rel="stylesheet" href="<?php echo $root_path; ?>css/bootstrap.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>

  <link href="<?php echo $root_path; ?>boxicons-2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>

<body>

  <script src="<?php echo $root_path; ?>js/bootstrap.js"></script>
  <div class="main-container ">
    <?php
    require $profile_path . 'navbar.php';
    student_navbar($root_path);
    ?>
    <section class="content-section row justify-content-center w-75">
      <div class="col-md-8">
        <canvas id="chartProgress"></canvas>
      </div>
    </section>
    <section class="content-section row justify-content-center">
      <div class="progressbars col-md-4 w-50">
        <?php
        foreach ($classrooms as $i) {
        ?>
          <label><?php echo  $i['classroom_name']; ?></label>
          <div class="progress my-2">
            <div class="progress-bar progressBar<?php echo  $i['class_code']; ?> progress-bar-animated bg-success" role="progressbar" style="width:0%" aria-valuenow="66.6" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <script>
            $(".progress-bar.progressBar<?php echo  $i['class_code']; ?>").animate({
              width: "70%",
            }, 250);
          </script>
        <?php
        }
        ?>
      </div>

    </section>
  </div>
  <script>
    var myChartCircle = new Chart('chartProgress', {
      type: 'doughnut',
      data: {
        datasets: [{
          label: 'Total percentage',
          percent: (50 * 100) / 75,
          backgroundColor: ['#2f6d8b']
        }]
      },
      plugins: [{
          beforeInit: (chart) => {
            const dataset = chart.data.datasets[0];
            chart.data.labels = [dataset.label];
            dataset.data = [dataset.percent, 100 - dataset.percent];
          }
        },
        {
          beforeDraw: (chart) => {
            var width = chart.chart.width,
              height = chart.chart.height,
              ctx = chart.chart.ctx;
            ctx.restore();
            var fontSize = (height / 90).toFixed(2);
            ctx.font = fontSize + "em sans-serif";
            ctx.fillStyle = "#9b9b9b";
            ctx.textBaseline = "middle";



            var text = chart.data.datasets[0].percent.toFixed(2);
            textX = Math.round((width - ctx.measureText(text).width) / 2.2),
              textY = height / 2;
            ctx.fillText(text + "%", textX, textY);
            ctx.save();
          }
        }
      ],
      options: {
        maintainAspectRatio: false,
        aspectRatio: 1,
        cutoutPercentage: 80,
        rotation: Math.PI / 2,
        legend: {
          display: false,
        },
        tooltips: {
          filter: tooltipItem => tooltipItem.index == 0
        }
      }
    });
  </script>
</body>

</html>