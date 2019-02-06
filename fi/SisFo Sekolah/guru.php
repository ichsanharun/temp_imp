<html>
<?php
include("connection.php");
session_start();
if (!isset($_SESSION['level'])) {
   ?><script>window.location.href="index.php";</script>
   <?php
}
$namasesi=$_SESSION['nama'];
$resultguru = $db->query("SELECT * FROM guru where nama_guru='$namasesi'");
$resultgallery = $db->query ("SELECT * FROM galeri");

 ?>


<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE-Edge">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Embun Pagi Islamic School</title>
  <!-- Bootstrap -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/bootstrap-theme.min.css" rel="stylesheet">
  <link href="css/custom.css" rel="stylesheet">
  <!--script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script-->

  <script type="text/javascript" src="js/jquery-3.1.1.js"></script>
  <script type="text/javascript" src="js/custom.js"></script>
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>

  <!-- HTML5 Support -->
  <script src="http://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="http://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
</head>
<body scrolling="no">
  <header>
    <div id="content" class="container">
      <div id="section-1" class="row">
        <div class="col-sm-10 col-sm-offset-1">
          <nav class="navbar navbar-default navbar-inverse navbar-fixed-top">
            <div class="logo span3">
              <a class="navbar-brand" href="#" ><img src="img/logo.png" alt="" height="35px"></a>
            </div>
            <div class="navbar-right">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
            </div>
            <div class="collapse navbar-collapse navbar-right" id="navbarCollapse">
              <ul id="nav" class="nav navbar-nav">
                <li><a href="guru.php">Profile</a></li>
                <li><a href="lihat_jadwal.php">Lihat Jadwal Mengajar</a></li>
                <li><a href="logout.php">Logout</a></li>
              </ul>
            </div>
          </nav>
      </div>
    </div>
  </div>
  </header>
  <section>
    <div class="container">
      <div class="row tengah">
        <div class="col-sm-12 tengah">
          <table class="table table-bordered table-responsive">
            <caption></caption>
            <thead>
              <tr>
                <th class="active">Profile</th>
                <th class="active">Keterangan</th>
              </tr>
            </thead>
            <?php while ($rowguru=$resultguru->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <tbody>
              <tr>
                <td>NIP</td>
                <td><?php echo $rowguru['nip'] ?></td>
              </tr>
              <tr>
                <td>Nama</td>
                <td><?php echo $rowguru['nama_guru'] ?></td>
              </tr>
              <tr>
                <td>Alamat</td>
                <td><?php echo $rowguru['alamat_guru'] ?></td>
              </tr>
              <tr>
                <td>Telepon</td>
                <td><?php echo $rowguru['no_tlp'] ?></td>
              </tr>
              <tr>
                <td>Tempat Lahir</td>
                <td><?php echo $rowguru['tempat_lahir'] ?></td>
              </tr>
              <tr>
                <td>Tanggal Lahir</td>
                <td><?php echo $rowguru['tanggal_lahir'] ?></td>
              </tr>
              <tr>
                <td>Mengajar Mulai</td>
                <td><?php echo $rowguru['mulai_mengajar'] ?></td>
              </tr>
              <tr>
                <td>Status Ajar</td>
                <td><?php echo $rowguru['status_pengajar'] ?></td>
              </tr>
            </tbody>
            <?php } ?>
        </div>
      </div>
    </div>
  </section>
<footer>
</footer>
<script type='text/javascript'>
//<![CDATA[
$(document).ready(function(){
$('a[href^="#"]').on('click',function (e) {
 e.preventDefault();

 var target = this.hash,
 $target = $(target);

 $('html, body').stop().animate({
     'scrollTop': $target.offset().top
 }, 900, 'swing', function () {
     window.location.hash = target;


 });
});
});
//]]>
</script>
</body>
</html>
