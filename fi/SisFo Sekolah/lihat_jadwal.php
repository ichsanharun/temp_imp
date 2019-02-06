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
$resultjadwal = $db->query("SELECT * FROM jadwal_pelajaran where nama_guru='$namasesi'");
$resultsenin = $db->query("SELECT * FROM jadwal_pelajaran where nama_guru='$namasesi' AND hari='senin'");
$resultselasa = $db->query("SELECT * FROM jadwal_pelajaran where nama_guru='$namasesi' AND hari='selasa'");
$resultrabu = $db->query("SELECT * FROM jadwal_pelajaran where nama_guru='$namasesi' AND hari='rabu'");
$resultkamis = $db->query("SELECT * FROM jadwal_pelajaran where nama_guru='$namasesi' AND hari='kamis'");
$resultjumat = $db->query("SELECT * FROM jadwal_pelajaran where nama_guru='$namasesi' AND hari='jumat'");
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
        <div class="col-sm-12">
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingSenin">
              <h4 class="panel-title">
                <a class="collapsed" style="text-align:center" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSenin" aria-expanded="false" aria-controls="collapseSenin">
                   Jadwal Mengajar Hari Senin
                </a>
              </h4>
            </div>
            <div id="collapseSenin" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSenin">
              <div class="panel-body">
                <table class="table table-bordered table-responsive">
                  <caption></caption>
                  <thead>
                    <tr>
                      <th class="active">Kelas</th>
                      <th class="active">Mata Pelajaran</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while ($rowsenin=$resultsenin->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                      <td><?php echo $rowsenin['nama_kelas'] ?></td>
                      <td><?php echo $rowsenin['nama_mapel'] ?></td>
                    </tr>
                  </tbody>
                  <?php } ?>
                </table>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingSelasa">
              <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSelasa" aria-expanded="false" aria-controls="collapseSelasa">
                   Jadwal Mengajar Hari Selasa
                </a>
              </h4>
            </div>
            <div id="collapseSelasa" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSelasa">
              <div class="panel-body">
                <table class="table table-bordered table-responsive">
                  <caption></caption>
                  <thead>
                    <tr>
                      <th class="active">Kelas</th>
                      <th class="active">Mata Pelajaran</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while ($rowselasa=$resultselasa->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                      <td><?php echo $rowselasa['nama_kelas'] ?></td>
                      <td><?php echo $rowselasa['nama_mapel'] ?></td>
                    </tr>
                  </tbody>
                  <?php } ?>
                </table>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingRabu">
              <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseRabu" aria-expanded="false" aria-controls="collapseRabu">
                   Jadwal Mengajar Hari Rabu
                </a>
              </h4>
            </div>
            <div id="collapseRabu" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingRabu">
              <div class="panel-body">
                <table class="table table-bordered table-responsive">
                  <caption></caption>
                  <thead>
                    <tr>
                      <th class="active">Kelas</th>
                      <th class="active">Mata Pelajaran</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while ($rowrabu=$resultrabu->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                      <td><?php echo $rowrabu['nama_kelas'] ?></td>
                      <td><?php echo $rowrabu['nama_mapel'] ?></td>
                    </tr>
                  </tbody>
                  <?php } ?>
                </table>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingKamis">
              <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseKamis" aria-expanded="false" aria-controls="collapseKamis">
                   Jadwal Mengajar Hari Kamis
                </a>
              </h4>
            </div>
            <div id="collapseKamis" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingKamis">
              <div class="panel-body">
                <table class="table table-bordered table-responsive">
                  <caption></caption>
                  <thead>
                    <tr>
                      <th class="active">Kelas</th>
                      <th class="active">Mata Pelajaran</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while ($rowkamis=$resultkamis->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                      <td><?php echo $rowkamis['nama_kelas'] ?></td>
                      <td><?php echo $rowkamis['nama_mapel'] ?></td>
                    </tr>
                  </tbody>
                  <?php } ?>
                </table>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingJumat">
              <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseJumat" aria-expanded="false" aria-controls="collapseJumat">
                   Jadwal Mengajar Hari Jumat
                </a>
              </h4>
            </div>
            <div id="collapseJumat" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingJumat">
              <div class="panel-body">
                <table class="table table-bordered table-responsive">
                  <caption></caption>
                  <thead>
                    <tr>
                      <th class="active">Kelas</th>
                      <th class="active">Mata Pelajaran</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while ($rowjumat=$resultjumat->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                      <td><?php echo $rowjumat['nama_kelas'] ?></td>
                      <td><?php echo $rowjumat['nama_mapel'] ?></td>
                    </tr>
                  </tbody>
                  <?php } ?>
                </table>
              </div>
            </div>
          </div>
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
