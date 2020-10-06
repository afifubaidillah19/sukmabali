<!DOCTYPE html>

<html>

<head>

	<title></title>

	<meta charset="utf-8">

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

  <link rel="stylesheet" type="text/css" href="{{asset('css/font.css')}}">

  <link rel="stylesheet" type="text/css" href="{{asset('css/succes.css')}}">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

</head>

<body>

	<div class="container-fluid">

		<div class="container">

			<nav class="navbar navbar-expand-md">

			  <!-- Brand -->

			  <a class="navbar-brand" href="#"><img src="image/logo3.png"></a>



			  <!-- Toggler/collapsibe Button -->

			  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">

			    <span class="navbar-toggler-icon" style="color: black"><img src="image/burger.png" style="height: 20px;"></span>

			  </button>



			  <!-- Navbar links -->

			  <div class="collapse navbar-collapse" id="collapsibleNavbar">

			  <ul class="navbar-nav">

				<li class="nav-item">

			        <a class="nav-link" href="{{URL('')}}">Home</a>

			      </li>

			      <li class="nav-item">

			        <a class="nav-link" href="#">Fitur</a>

			      </li>

			      <li class="nav-item">

			        <a class="nav-link" href="#">Kontak</a>

				  </li>

				  <li class="nav-item">

			        <a class="nav-link" href="{{URL('admin/auth/login')}}">Masuk</a>

			      </li>

				  <li class="nav-item">

			        <a class="nav-link" href="{{URL('register')}}">Daftar</a>

			      </li>

			      <li class="nav-item">

			        <a class="nav-link" href="https://play.google.com/store/apps/details?id=com.sukmabali" target="_blank">Download</a>

			      </li>

			    </ul>

			  </div>

			</nav>



			<div class="banner">

				<div class="col-md-12">

					<h2>Selamat Pendaftaran anda berhasil !</h2>

					<p>Silahkan login menggunakan <b>Nomor Telepon</b> yang digunakan saat mendaftar</p>

 				<div class="btn-center">
				 <a href="https://play.google.com/store/apps/details?id=com.sukmabali" target="_blank"><button>Download Aplikasi</button></a>
 					

 				</div>

				</div>

			</div>	



		</div>

	</div>

</body>

</html>