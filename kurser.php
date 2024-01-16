<!DOCTYPE html>
<html lang="sv">

<head>
  <meta charset="utf-8">
  <title>Kurser</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="js/script.js"></script>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
    integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- CSS-fil -->
  <link rel="stylesheet" href="kurser.css">
</head>

<body>

  <header>
    <?php include 'Components/Navbar.php'; ?>
  </header>

  <!-- 12 Content boxes -->
  <div class="container" id="box-container">
    <!-- Första gruppen med boxar -->
    <div class="row box-group" id="group1">
      <!-- Box 1-6 -->
      <?php for ($i = 1; $i <= 6; $i++) { ?>
        <div class="col-lg-4 col-md-6 col-sm-6">
          <div class="box">
            <h4>Rubrik <?php echo $i; ?></h4>
            <p>Beskrivning av kursen <?php echo $i; ?>.</p>
          </div>
        </div>
      <?php } ?>
    </div>
    <!-- Andra gruppen med boxar (initialt dolda) -->
    <div class="row box-group hidden" id="group2">
      <!-- Box 7-12 -->
      <?php for ($i = 7; $i <= 12; $i++) { ?>
        <div class="col-lg-4 col-md-6 col-sm-6">
          <div class="box">
            <h4>Rubrik <?php echo $i; ?></h4>
            <p>Beskrivning av kursen <?php echo $i; ?>.</p>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>

  <!-- Knappar -->
  <div class="button-container">
    <button id="showGroupLeft" class="rounded-button left"><i class="fa fa-chevron-left"></i></button>
    <button id="showGroupRight" class="rounded-button right"><i class="fa fa-chevron-right"></i></button>
  </div>

  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>

  <script>
    var boxContainer = document.getElementById('box-container');
    var group1 = document.getElementById('group1');
    var group2 = document.getElementById('group2');

    // Lägg till händelsehanterare för klick händelsen på knapparna
    document.getElementById('showGroupLeft').addEventListener('click', function () {
      group1.classList.add('hidden');
      group2.classList.remove('hidden');
    });

    document.getElementById('showGroupRight').addEventListener('click', function () {
      group1.classList.remove('hidden');
      group2.classList.add('hidden');
    });
  </script>

</body>

</html>
