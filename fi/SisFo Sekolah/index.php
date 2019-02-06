<html>
<?php
include("connection.php");
$resultliving = $db->query ("SELECT * FROM komentar");
$resultgallery = $db->query ("SELECT * FROM galeri");
session_start();
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
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="#facilities">Facilities</a></li>
                <li><a href="#living">Living Room</a></li>
                <li><a href="#gallery">Photo Gallery</a></li>
                <li><a href="#living">Contact Us</a></li>
                <li><a href="#login" id="opener">Login</a></li>
              </ul>
            </div>
          </nav>
      </div>
    </div>
  </div>
  </header>
  <div id="content">
    <section id="section-1">

       <style> .tengah{margin-top:75px;}
       .row-table {
         table-layout: fixed;

         width: 100%;

         height: 100%;}


        }
       </style>
      <div id="home" class="row row-table rad">
        <div class="col-sm-12 fixed tengah" style="width:100%" style="max-height:400px">
          <div id="slide1" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
              <li data-target="#slide1" data-slide-to="0" class="active"></li>
              <li data-target="#slide1" data-slide-to="1"></li>
              <li data-target="#slide1" data-slide-to="2"></li>
              <li data-target="#slide1" data-slide-to="3"></li>
              <li data-target="#slide1" data-slide-to="4"></li>
              <li data-target="#slide1" data-slide-to="5"></li>
              <li data-target="#slide1" data-slide-to="6"></li>
              <li data-target="#slide1" data-slide-to="7"></li>
            </ol>

            <!--carousel items-->
            <div class="carousel-inner" role="listbox" align="center">
              <div class="item active">
                <img src="img/slide1.jpg" alt="" width="55%">
              </div>
              <div class="item">
                <img src="img/slide2.jpg" alt="" width="55%">
              </div>
              <div class="item">
                <img src="img/slide3.jpg" alt="" width="55%">
              </div>
              <div class="item">
                <img src="img/slide4.jpg" alt="" width="55%">
              </div>
              <div class="item">
                <img src="img/slide5.jpg" alt="" width="55%">
              </div>
              <div class="item">
                <img src="img/slide6.jpg" alt="" width="55%">
              </div>
              <div class="item">
                <img src="img/slide7.jpg" alt="" width="55%">
              </div>
            </div>

            <!--Carousel Control-->
            <a class="left carousel-control" href="#slide1" role="button" data-slide="prev"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a>
            <a class="right carousel-control" href="#slide1" role="button" data-slide="next"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>
          </div>
        </div>
        <div id="i" class="divider"></div>
        <h1 class="blue wadah-mengetik" align="center"><p><strong class="blink">WELCOME TO<br>EMBUN PAGI ISLAMIC SCHOOL SITE </p></strong></h1>
      </div>
    </section>
    <section id="section-2">
      <div class="container rad">
        <div id="about" class="row row-table">
          <div class="col-sm-12 right tengah">
            <h2 class="blue" style="text-align:right">
              About Us
            </h2>
            <div class="col-sm-3">
              <img src="img/pre.jpg" width="100%">
            </div>
            <div class="col-sm-4">
              <p>
              <h4><strong>The Vision</strong></h4>
              <li>Developing islamic leaders of the future</li>
              <h4><strong>The Mision</strong></h4>
              <li>Making akhlaqul karimah as a habit</li>
              <li>Creating a leadership learning environment</li>
              <li>Exploring the children's potentials</li>
              <h5><strong>Goal</strong></h5>
              <li>To become the leading islamic school with a global vision</li>
              <h4><strong>THE VALUES WE EMBED IN OUR STUDENTS  MIND :</strong></h4>
              <h4><strong>The International Insight  :</strong></h4>
              <li>Using English in the teaching/learning process.
              <li>International methodology.
              <li>Ready to face the globalization era with deep cultural and Islamic roots.
              <li>Problem solving oriented.
              <li>Good communication.
              <li>Cultural awareness.AND hari='senin'
              </p>
            </div>
            <div class="col-sm-4">
              <p>
              <h4><strong>The Islamic Values :</strong></h4>
              <li>Recognize and love Allah SWT and His Prophet Muhammad SAW.</li>
              <li>Understanding the true meaning of sholat and what the Al-quran surah/verses imply.</li>
              <li>Living in an Islamic Life based on Al-quran and Hadits.</li>
              <li>Devoted Moslem with good morals.</li>
              <li>Proud of being a Moslem.</li>
              <li>Role model citizens.</li>
              <li>Highly-disciplined individuals.</li>
              <li>Appreciative and respectful to others.</li>
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section id="section-3">
      <div class="container rad">
        <div id="facilities" class="row row-table">
          <div class="col-sm-12 fixed tengah" style="width:100%">
            <h2 class="blue" style="text-align:center">
              Facilities
            </h2>
            <div id="slide2" class="carousel slide" data-ride="carousel" style="width:100%">
              <ol class="carousel-indicators">
                <li data-target="#slide2" data-slide-to="0" class="active"></li>
                <li data-target="#slide2" data-slide-to="1"></li>
                <li data-target="#slide2" data-slide-to="2"></li>
                <li data-target="#slide2" data-slide-to="3"></li>
                <li data-target="#slide2" data-slide-to="4"></li>
                <li data-target="#slide2" data-slide-to="5"></li>
              </ol>

              <!--carousel items-->
              <div class="carousel-inner" role="listbox" align="center">
                <div class="item active">
                  <img src="img/f1.jpg" alt="" width="55%">
                </div>
                <div class="item">
                  <img src="img/f2.jpg" alt="" width="55%">
                </div>
                <div class="item">
                  <img src="img/f3.jpg" alt="" width="55%">
                </div>
                <div class="item">
                  <img src="img/f4.jpg" alt="" width="23%">
                </div>
                <div class="item">
                  <img src="img/f5.jpg" alt="" width="23%">
                </div>
                <div class="item">
                  <img src="img/f6.jpg" alt="" width="23%">
                </div>
              </div>

              <!--Carousel Control-->
              <a class="left carousel-control" href="#slide2" role="button" data-slide="prev"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a>
              <a class="right carousel-control" href="#slide2" role="button" data-slide="next"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section id="section-4">
      <div class="container rad">
        <div id="living" class="row row-table">
          <div class="col-sm-12 tengah" style="width:100%">
            <h2 class="blue" style="text-align:center">
              Living Room
            </h2>
            <div class="col-sm-5 col-sm-offset-1 radcol" style="width:auto">
              <?php while ($rowliving = $resultliving->fetch(PDO::FETCH_ASSOC)) {
              ?>
              <div class="panel panel-default" width="100%">
                <div class="panel-heading" style="width:600px">
                  <h3 class="panel-title"><?php echo $rowliving['nama'];?></h3>
                </div>
                <div class="panel-body">
                  <p><?php echo $rowliving['komentar']; ?></p>
                </div>
              </div>
              <?php } ?>
            </div>
            <div class="col-sm-4 radcol">
              <form class="" action="" style="width:auto-layout" method="post">
                <div class="form-group">
                  <label for="namaLiving">Name</label>
                  <input type="text" class="form-control" name="namaliving" id="inputnama" placeholder="Nama Lengkap">
                </div>
                <div class="form-group">
                  <label for="emailLiving">Email</label>
                  <input type="email" class="form-control" name="emailliving" id="inputemail" placeholder="Email">
                </div>
                <div class="form-group">
                  <label for="komentarLiving">Message</label>
                  <textarea class="form-control" name="komentarliving" id="inputkomentar" placeholder=""></textarea>
                </div>
                <button type="submit" class="btn btn-default" onclick="getContent(aksiliving())" on>Submit</button>
              </form>
<!--aksi living ----------------------------------------------->
              <?php
              if ($_SERVER['REQUEST_METHOD'] == "POST") {
                if (isset($_POST['namaliving'])) {
                  $nameliving=$_POST['namaliving'];
              $emailliving=$_POST['emailliving'];
              $komentarliving=$_POST['komentarliving'];
              $simpanliving = $db->query("INSERT into komentar VALUES('$nameliving','$emailliving','$komentarliving')");
              if ($simpanliving) {
                echo "file terupload";
              }
              else {
                echo "x";
              }
            }}
            else {

            }
               ?>
<!--end aksi living ----------------------------------------------->
          </div>
          <div class="divider"></div>
          <div class="col-sm-3 panel-default" style="margin:10px">
            <ul class="radcol">
              <h4><strong>Address</strong></h4>
              <li><span class="glyphicon glyphicon-home">Preschool
Jl. Raya Kalimalang No 39 (Gedung ILP Kalimalang) Jakarta Timur 13440
Elementary school
Jl. Raya Kapin No 8 Kalimalang Jakarta Timur 13450  </span></li>
            </ul>
          </div>
         </div>
        </div>
      </div>
    </section>
    <section id="section-5">
      <div class="container rad">
        <div id="gallery" class="row row-table">
          <div class="col-sm-10 col-sm-offset-1 tengah">
            <h2 class="blue" style="text-align:center">
              Photo Gallery
            </h2>
            <div class="col-sm-4">
              <?php while ($rowgallery = $resultgallery->fetch(PDO::FETCH_ASSOC)) {
              ?>
              <div class="thumbnail">
                <img src="img/<?php echo $rowgallery['lokasi']; ?>" alt="">
                <div class="caption"><h6><?php echo $rowgallery['nama_album']; ?></h6></div>
              </div>
              <?php } ?>
            </div>
          </div>
         </div>
        </div>
    </section>
    <section>
      <div class="container rad">
        <div id="login" class="row row-table">
          <div class="col-sm-10 col-sm-offset-1 tengah">
            <h2 class="blue text-center">Login</h2>
            <div class="login-form login" id="dialog">
                    <form method="post">
                      <div class="form-group">
                        <label for="emaillogin">E-Mail</label>
                        <input type="email" class="form-control" name="emaillogin" id="inputemail" placeholder="E-Mail Anda">
                      </div>
                      <div class="form-group">
                        <label for="passlogin">Password</label>
                        <input type="password" class="form-control" name="passlogin" id="inputpass" placeholder="Password Anda"></textarea>
                      </div>
                      <button type="submit" class="btn btn-default tombol">Submit</button>
                    </form>
                  </div>
          </div>
         </div>
        </div>
        <!--aksi login ----------------------------------------------->
                      <?php
                      if ($_SERVER['REQUEST_METHOD'] == "POST") {
                        if (isset($_POST['emaillogin']) && ($_POST['passlogin'])) {
                          $emaillogin=$_POST['emaillogin'];
                          $passlogin=$_POST['passlogin'];
                          $sqllogin=$db->query("SELECT * FROM admin where email='$emaillogin' AND password='$passlogin'");
                          $resultlogin=$db->query("SELECT COUNT(*) FROM admin where email='$emaillogin' AND password='$passlogin'")->fetchColumn();
                          $rowlogin=$sqllogin->fetch(PDO::FETCH_ASSOC);
                          if ($resultlogin==1) {
                            $_SESSION['email'] = $rowlogin['email'];
                            $_SESSION['nama'] = $rowlogin['nama_lengkap'];
                            $_SESSION['level'] = $rowlogin['hak_akses'];
                            $_SESSION['pesan'] = '<p><div class="alert alert-success">Selamat datang di www.itoez.com<b>'.$_SESSION['nama'].'</b> Anda login dengan level : <b>'.$_SESSION['level'].'</b></div></p>';
                            echo "sukses";?>
                            <script>window.location.href="guru.php";</script><?php
                          }
                          else {
                            echo "gagal";
                          }
                        }
                      }
                       ?>
        <!--end aksi login ----------------------------------------------->
    </section>
  </div>
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
