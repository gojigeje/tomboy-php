<?php 
  include 'include/tomboy.php';

  if ($_REQUEST["note"] == "") {
     $active = "active";
  }
 ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/favicon.png">

    <title>tomboy@gojibuntu</title>

    <!-- Bootstrap core CSS -->
    <!-- <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootswatch/3.0.3/united/bootstrap.min.css">

    <!-- Custom styles for this template -->
    <link href="include/offcanvas.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <div class="navbar navbar-fixed-top navbar-inverse" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href=".">tomboy@gojibuntu</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="<?=$active?>"><a href=".">Home</a></li>
            <li><a target="_blank" href="//wiki.gnome.org/Apps/Tomboy/">Tomboy Notes ?</a></li>
            <li><a href="//keren.sejak.tk">keren.sejak.tk</a></li>
            <li><a href="//sejak.tk">sejak.tk</a></li>
          </ul>
        </div><!-- /.nav-collapse -->
      </div><!-- /.container -->
    </div><!-- /.navbar -->

    <div class="container">

      <div class="row row-offcanvas row-offcanvas-right">
        
        <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
          <div class="list-group">
            <a class="list-group-item active" href="#"><b>Catatanku :</b></a>
            <?php foreach($notes as $note) { ?>
                <a class="list-group-item" href=".?note=<?=$note["id"]?>"><b><?=$note["content"]["title"];?></b></a>
            <?php } ?>
          </div>
        </div><!--/span-->

        <div class="col-xs-12 col-sm-9">
          <p class="pull-left visible-xs">
            <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Tampilkan Catatan</button>
          </p>

          <?php if ($_REQUEST["note"] == ""): ?>
             <div class="jumbotron">
               <h1>Tomboy Notes @gojibuntu</h1>
               <p>Hai.. WebApp ini akan menampilkan catatan Tomboy Notes yang ada di laptopnya Goji.</p>
               <p>Klik pada judul catatan di sebelah kiri untuk menampilkan isinya :)</p>
             </div>
          <?php else:
            $notes = getNotes();
            $note = $notes[$_REQUEST["note"]];
            $note["content"] = getNote($note["id"], $note["rev"], true); ?>

            <div class="row">
               <div class="well col-12">
                  <?php echo autolink($note["content"]["text"], array("target"=>"_blank"));?>
               </div>
            </div><!--/row-->

          <?php endif ?>

        </div><!--/span-->

      </div><!--/row-->

      <hr>

      <footer>
        <p>&copy; 2013 <a target="_blank" href="//keren.sejak.tk">@gojibuntu</a> , a lovely server by <a target="_blank" href="//twitter.com/gojigeje">@gojigeje</a> :)</p>
      </footer>

    </div><!--/.container-->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
    <script src="include/offcanvas.js"></script>
  </body>
</html>
