<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  {{--  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">  --}}
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> 
  <link rel="stylesheet" type="text/css" href="{{asset('css/font.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">
  <link href="{{asset('image/logofav.jpeg')}}" rel="icon">
  {{--  <link rel="stylesheet" type="text/css" href="{{asset('css/style2.css')}}">  --}}

  {{--  <!-- Libraries CSS Files -->  --}}
  <link href="{{asset('lib/animate/animate.min.css')}}" rel="stylesheet">
  <link href="{{asset('lib/ionicons/css/ionicons.min.css')}}" rel="stylesheet">
  <link href="{{asset('lib/owlcarousel/assets/owl.carousel.min.css')}}" rel="stylesheet">
  <link href="{{asset('lib/lightbox/css/lightbox.min.css')}}" rel="stylesheet">
  

  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/v4-shims.css">
  
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
			        <a class="nav-link" href="/">Home</a>
			      </li>
			      <li class="nav-item">
			        <a class="nav-link" href="#fitur">Fitur</a>
			      </li>
			      <li class="nav-item">
			        <a class="nav-link" href="#kontak">Kontak</a>
				  </li>
				  <li class="nav-item">
			        <a class="nav-link" href="{{URL('admin/auth/login')}}">Masuk</a>
			      </li>
				  <li class="nav-item">
			        <a class="nav-link" href="{{URL('register')}}">Daftar</a>
			      </li>
			      <!-- <li class="nav-item">
			        <a class="nav-link" href="{{URL('about')}}">Tentang</a>
			      </li> -->
			      <li class="nav-item">
			        <a class="nav-link" href="https://play.google.com/store/apps/details?id=com.sukmabali" target="_blank">Download</a>
			      </li>
			    </ul>
			  </div>
			</nav>
			<div class="row banner d-flex align-items-center">
				<div class="col-md-6">
					<h2>Belanja Murah Bisnis Mudah Dari Rumah</h2>
					<p class="text-justify">SukmaBali adalah sebuah sistem berbasis e-marketplace yang mampu mengelola badan-badan 
					usaha kecil seperti warung, UMKM, petani, nelayan, dan lain-lain di sebuah desa.</p>
					<p class="text-justify">Dengan SukmaBali pembeli dan penjual bisa berkerja sama dalam satu wadah bisnis dengan potensi <a href="#income"><strong>Pasif Income</strong></a> yang luar biasa.</p>
<div>
 	<a href="https://play.google.com/store/apps/details?id=com.sukmabali" target="_blank"><button>Download App</button></a>
</div>
			</div>
				<div class="col-md-6 d-flex">
					<img src="{{asset('image/SukmaBali.png')}}" >
				</div>
			</div>	
			
			<div class="row content end-section" style="margin-top: 120px;" id="fitur">
				<div style="text-align: center; width: 100%; font-family: PRegular; font-size: 40px;" >
					Kenapa Berjualan di <strong>SukmaBali</strong>
				</div>
				<div style="background: linear-gradient(169deg, #FECC92 0%, #FECC92 0%, #FF0100 100%); height: 7px; width: 95px; position: relative;left: 50%; transform: translateX(-50%); margin-top: 20px; border-radius: 20px;">
					
				</div>
				<div style="width: 100%; margin-top: 80px; flex-wrap: wrap;" class="d-flex justify-content-between">
					<div class="col-md-3">
						<img src="{{asset('image/1.png')}}">
						<h2>Cash On Delivery</h2>
						<p>Transaksi langsung tunai antara penjual dan pembeli.</p>
					</div>
					<div class="col-md-3">
						<img src="{{asset('image/3.png')}}">
						<h2>Potensi Pasif Income</h2>
						<p>Dapat membentuk jaringan konsumen dengan skema bisnis SukmaBali.</p>
					</div>
					<div class="col-md-3">
						<img src="{{asset('image/6.png')}}">
						<h2>Jangkauan Pasar Lebih Luas</h2>
						<p>Dengan online marketing, konsumen bisa mengetahui keberadaan toko online yang terdaftar di SukmaBali.</p>
					</div>
				</div>
			</div>	
		</div>
	
		<!--==========================
			Features Section
		  ============================-->
		  <section id="features">
			<div class="container">
				<header class="section-header">
					<h2>Kenapa Berbelanja di <strong>SukmaBali</strong></h2>
					<div style="background: linear-gradient(169deg, #FECC92 0%, #FECC92 0%, #FF0100 100%); height: 7px; width: 95px; position: relative;left: 50%; transform: translateX(-50%); margin-top: 20px; margin-bottom:70px; border-radius: 20px;">
				  </header>
			  <div class="row feature-item">
				<div class="col-lg-6 wow fadeInUp">
				  <img src="{{asset('image/4.png')}}" class="img-fluid" alt="">
				</div>
				<div class="col-lg-6 wow fadeInUp pt-5 pt-lg-0">
				  <h4>Belanja Lebih Gampang Dengan Harga Yang Terjangkau</h4>
				  <p class="text-justify">
					Di SukmaBali, kami berkomitmen untuk membantu anda dalam berbelanja dengan lebih mudah. Dengan lebih dari 100 ragam produk, mulai dari makanan hingga aksesoris, dan diperoleh langsung dari penjual terdekat anda. 
				  </p>
				  <p class="text-justify">
					SukmaBali memastikan anda akan mendapatkan produk apapun yang anda inginkan dengan lebih mudah dan tentu saja murah.
				  </p>
				</div>
			  </div>
	  
			  <div class="row feature-item mt-5 pt-5">
				<div class="col-lg-6 wow fadeInUp order-1 order-lg-2">
				  <img src="{{asset('image/2.png')}}" class="img-fluid" alt="">
				</div>
				
				<div class="col-lg-6 wow fadeInUp pt-4 pt-lg-0 order-2 order-lg-1">
				  <h4>Potensi Pasif Income</h4>
				  <p class="text-justify">
					Di SukmaBali, anda tidak hanya dapat sekadar melakukan jual beli seperti biasa saja. Sembari melakukan jual beli, anda juga berpotensi mendapatkan penghasilan tambahan secara pasif, lho!. 
				  </p>
				  <p class="text-justify">
					Setiap anda mengundang pengguna baru untuk ikut membeli maupun berjualan produk di SukmaBali, potensi passive income anda juga akan bertambah tinggi.
				  </p>
				</div>
				
			  </div>
	  
			</div>
		  </section>
		  <!-- #about -->

	<!--==========================
		Frequently Asked Questions Section
	  ============================-->
	  <section id="faq">
		<div class="container">
		  <header class="section-header">
			<h2>Frequently <strong>Asked Questions</strong></h2>
			<div style="background: linear-gradient(169deg, #FECC92 0%, #FECC92 0%, #FF0100 100%); height: 7px; width: 95px; position: relative;left: 50%; transform: translateX(-50%); margin-top: 20px; margin-bottom:70px; border-radius: 20px;">
			</header>
  
		  <ul id="faq-list" class="wow fadeInUp">
			<li>
			  <a data-toggle="collapse" class="collapsed" href="#faq1">Bagaimana cara kerja bisnis SukmaBali? <i class="ion-android-remove"></i></a>
			  <div id="faq1" class="collapse" data-parent="#faq-list">
				<p class="text-justify">
					SukmaBali mengajak anda untuk bergotong-royong bersama pedagang di sekitar lingkungan desa anda, sedemikian sehingga membentuk sebuah pasar online desa. Tujuannya adalah saling membantu baik pedagang maupun pembeli. Pedagang akan semakin laris dagangannya, sementara pembeli pun mendapat keuntungan karena bisa belanja hanya dari rumah saja.
				</p>
				<p>
					Konsep ini akan benar-benar membantu saudara-saudara kita yang ingin berjualan namun tidak punya modal besar dalam membuat toko atau warung. anda sebagai pembeli bisa sewaktu-waktu bertindak sebagai penjual produk-produk anda sendiri (tentu saja, produk yang tidak melanggar hukum).
				</p>
			  </div>
			</li>
  
			<li>
			  <a data-toggle="collapse" href="#faq2" class="collapsed">Bagaimana caranya berbelanja bisa menjadi penghasilan? <i class="ion-android-remove"></i></a>
			  <div id="faq2" class="collapse" data-parent="#faq-list">
				<p class="text-justify">
					SukmaBali mengadopsi konsep pemasaran jaringan, sebuah konsep yang sangat ampuh untuk penjualan produk-produk berkualitas tinggi. Konsep ini mampu menghadirkan rasa memiliki akan bisnis yang dijalankan. Namun tak perlu khawatir, tidak seperti bisnis jaringan lainnya yang membutuhkan modal yang besar sehingga sulit mengajak orang untuk menjalankan bisnisnya, SukmaBali adalah bisnis jaringan kebutuhan rumah tangga. Hal ini berarti siapapun tidak akan merasa terbebani menjalankan bisnis ini. Konsep bisnisnya pun dibuat sesederhana mungkin sehingga tidak membuat calon jaringan anda kebingungan. <a href="#income" style="font-size: 16px;">Baca Selengkapnya >></a>
				</p>
			  </div>
			</li>
  
			<li>
			  <a data-toggle="collapse" href="#faq3" class="collapsed">Mungkin anda bertanya-tanya, mengapa bonusnya sangat kecil, hanya 0,1 persen? <i class="ion-android-remove"></i></a>
			  <div id="faq3" class="collapse" data-parent="#faq-list">
				<p class="text-justify">
					Sebagai informasi, bonus 0,1 persen tersebut didapatkan dari nilai belanja. Nilai 0,1 persen ini dikarenakan SukmaBali hanya meminta upgrade nilai produk 1 persen saja ke penjual, sehingga harga produk di tangan konsumen masih tetap murah dan tidak membebani. Di sinilah konsep gotong-royong lainnya dari SukmaBali.
				</p>
			  </div>
			</li>
  
			<li>
			  <a data-toggle="collapse" href="#faq4" class="collapsed">Apakah pendaftaran di SukmaBali dikenakan biaya? <i class="ion-android-remove"></i></a>
			  <div id="faq4" class="collapse" data-parent="#faq-list">
				<p class="text-justify">
					Di SukmaBali, pendaftaran benar-benar 100 persen gratis!
				</p>
			  </div>
			</li>
  
			<li>
			  <a data-toggle="collapse" href="#faq5" class="collapsed">Apakah berbelanja di SukmaBali sulit? <i class="ion-android-remove"></i></a>
			  <div id="faq5" class="collapse" data-parent="#faq-list">
				<p class="text-justify">
					Transaksi di SukmaBali mengusung konsep pembayaran tunai ketika produk diantarkan langsung ke alamat pembeli (juga dikenal dengan Cash-On-Delivery). Jadi, pembeli tak perlu bingung memikirkan transfer uang, hanya perlu menyiapkan uang pas sesuai yang tertera di aplikasi saat memesan produk.
			  </div>
			</li>
  
			<li>
			  <a data-toggle="collapse" href="#faq6" class="collapsed">Apakah ada syarat tertentu sebagai penjual di SukmaBali? <i class="ion-android-remove"></i></a>
			  <div id="faq6" class="collapse" data-parent="#faq-list">
				<p>
				  Nah, jika anda adalah penjual, anda diwajibkan melakukan top-up saldo deposit penjual sebesar Rp. 100.000. Nantinya setiap transaksi yang terjadi dengan produk-produk anda, maka deposit inilah yang akan dipotong oleh manajemen SukmaBali, hanya sebesar 1 persen saja dari nilai transaksi anda. Di mana lagi e-commerce yang memotong hanya 1 persen? Hanya di SukmaBali, bukan? Karena kita mengusung konsep bisnis gotong-royong.
				</p>
			  </div>
			</li>

			<li>
			  <a data-toggle="collapse" href="#faq7" class="collapsed">Saya masih memiliki pertanyaan, ke mana saya dapat bertanya? <i class="ion-android-remove"></i></a>
			  <div id="faq7" class="collapse" data-parent="#faq-list">
				<p>
				  Nah, kalo masih ada yang mau ditanyakan, jangan ragu untuk menghubungi CS SukmaBali, atau datang langsung ke alamat kami di Jalan Ahmad Yani Utara Gg. Sriti II No 3, Denpasar.
				</p>
				<p>
					SukmaBali bukan aplikasi bisnis biasa, tetapi bisnis untuk semua kalangan masyarakat dengan potensi penghasilan yang sangat menjanjikan. Buktikan sendiri! 
				</p>
				<p>
					Tunggu apa lagi, segera buktikan sendiri dengan mendaftar pada aplikasi SukmaBali di sini!
				</p>
			  </div>
			</li>  

		  </ul>
  
		</div>
	  </section>
	
	  <!-- #faq -->

	 <section id="income">
			<div class="container" style="margin-bottom: 7rem;">
				<header class="section-header">
					<h2>Potensi <strong>Pasif Income</strong></h2>
					<div style="background: linear-gradient(169deg, #FECC92 0%, #FECC92 0%, #FF0100 100%); height: 7px; width: 95px; position: relative;left: 50%; transform: translateX(-50%); margin-top: 20px; margin-bottom:70px; border-radius: 20px;">
				  </header>
				  <div class="row feature-item">
					<div class="col-lg-7 wow fadeInUp">
					  <h4><strong>Tabel Estimasi Penghasilan</strong></h4>
					  <table class="table table-striped">
					  <thead>
					    <tr>
					      <th scope="col">Generasi</th>
					      <th scope="col">Member anda</th>
					      <th scope="col">Persentasi Bonus</th>
					      <th scope="col">Rata belanja member</th>
					      <th scope="col">Estimasi penghasilan</th>
					    </tr>
					    			
					  </thead>
					  <tbody>
					    <tr>
					      <th scope="row">1</th>
					      <td>10</td>
					      <td>0.1%</td>
					      <td>Rp. 50.000</td>
					      <td>Rp. 500</td>
					    </tr>
					    <tr>
					      <th scope="row">2</th>
					      <td>110</td>
					      <td>0.1%</td>
					      <td>Rp. 50.000</td>
					      <td>Rp. 5.500</td>
					    </tr>
					    <tr>
					      <th scope="row">3</th>
					      <td>1110</td>
					      <td>0.1%</td>
					      <td>Rp. 50.000</td>
					      <td>Rp. 55.500</td>
					    </tr>
					    <tr>
					      <th scope="row">4</th>
					      <td>11110</td>
					      <td>0.1%</td>
					      <td>Rp. 50.000</td>
					      <td>Rp. 555.500</td>
					    </tr>
					  </tbody>
					</table>
				</div>
				<div class="col-lg-5 wow fadeInUp pt-5 pt-lg-0">
				  <h5>Potensi Pasif Income yang Luar Biasa</h5>
				  <p class="text-justify">
				  	Misalkan anda mengajak 10 orang di jaringan bawah anda secara langsung (kita sebut dengan generasi pertama) dan menularkan cara ini sampai 4 generasi. Lalu misalkan juga masing-masing orang di jaringan anda berbelanja hanya Rp. 50.000 saja sebulan.
				  </p>
				  <p class="text-justify">
					Namun harap diingat, bonus hanya datang dari 4 generasi saja agar bisa sama-sama diuntungkan, tetapi sebuah generasi tidak dibatasi jumlah anggotanya. Hal ini agar memberi keuntungan lebih besar untuk anggota yang lebih giat dalam bekerja di SukmaBali.

				  </p>
				  <p class="text-justify">
					Tentu saja belanja Rp. 50.000 per bulan untuk kebutuhan sehari-hari adalah anggapan yang sangat pesimis, bukan? Coba pikirkan kembali, kira-kira berapakah kebutuhan pribadi anda selama sebulan? Bayangkan semua transaksi tersebut dilakukan melalui SukmaBali, tentu akan jadi potensi yang sangat besar, bukan?
				  </p>
				</div>
			  </div>
	  
			</div>
		  </section>
	  </div>
	  

	
	  <footer>
        	<div class="footer-top" id="kontak">
		        <div class="container">
		        	<div class="row">
		        		<div class="col-md-4 col-lg-3 footer-about wow fadeInUp">
		        			<!-- <img class="logo-footer" src="assets/img/logo.png" alt="logo-footer" data-at2x="assets/img/logo.png"> -->
		        			<h3>About Us</h3>
		        			<p>
		        				SukmaBali adalah sebuah sistem berbasis e-marketplace yang mampu mengelola badan-badan usaha kecil seperti warung, UMKM, petani, nelayan, dan lain-lain di sebuah desa.
		        			</p>
		        			<a href="https://play.google.com/store/apps/details?id=com.sukmabali" target="_blank"><img src="{{asset('image/googleplay.png')}}"></a>	
		        			<!-- <p><a href="#">Our Team</a></p> -->
	                    </div>
		        		<div class="col-md-4 col-lg-4 offset-md-1 footer-contact wow fadeInDown">
		        			<h3>Contact</h3>
		                	<p><a href="https://www.google.com/maps/dir/-8.6306312,115.2106866//@-8.6307241,115.2104154,19.5z" target="_blank"><i class="fas fa-map-marker-alt"></i>Jl. A. Yani Utara, Sriti Residence Gg.2 No. 3, Denpasar, Bali</a></p>
		                	<p><a href="tel:0812-3633-6463"><i class="fas fa-phone"></i>0812-3633-6463</a></p>
		                	<p><a href="https://mail.google.com/mail/u/0/?tab=rm&ogbl#inbox?compose=new"><i class="fas fa-envelope"></i>sukmabali2020@gmail.com</a></p>
	                    </div>

	                    <div class="col-md-4 col-lg-3 offset-md-1 footer-social wow fadeInUp">
	                    	<h3>Follow Us On</h3>
	                    	<p><a href="https://www.facebook.com/SukmaBaliApps/" target="_blank"><i class="fab fa-facebook"></i>Sukmabali</a></p>
	                    	<p><a href="https://www.instagram.com/sukmabali_id/?hl=en" target="_blank"><i class="fab fa-instagram"></i>sukmabali_id</a></p>
							<p><a href="https://www.youtube.com/channel/UCdJib4trzH6bhZqSPLI16Bw?view_as=subscriber" target="_blank"><i class="fab fa-youtube"></i>Sukma Bali</a></p>
	                    </div>
		            </div>
		        </div>
	        </div>
	        <div class="footer-bottom">
	        	<div class="container">
	        		<div class="row">
	           			<div class="col-md-12 footer-copyright text-center">
	                    	<p>&copy; All Rights Reserved Team <a href="https://sukmabali.com"><strong>SukmaBali</strong></a></p>
	                    </div>
	           		</div>
	        	</div>
	        </div>
		</footer>

		<a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
		

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

	   <!--  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
		
		<script src="{{asset('lib/jquery/jquery.min.js')}}"></script>
		<script src="{{asset('lib/jquery/jquery-migrate.min.js')}}"></script>
		<script src="{{asset('lib/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
		<script src="{{asset('lib/easing/easing.min.js')}}"></script>
		<script src="{{asset('lib/mobile-nav/mobile-nav.js')}}"></script>
		<script src="{{asset('lib/wow/wow.min.js')}}"></script>
		<script src="{{asset('lib/waypoints/waypoints.min.js')}}"></script>
		<script src="{{asset('lib/counterup/counterup.min.js')}}"></script>
		<script src="{{asset('lib/owlcarousel/owl.carousel.min.js')}}"></script>
		<script src="{{asset('lib/isotope/isotope.pkgd.min.js')}}"></script>
		<script src="{{asset('lib/lightbox/js/lightbox.min.js')}}"></script>
		<script src="{{asset('js/main.js')}}"></script>
		
	  	{{--  <script type="text/javascript">
		    $(function(){
		      var navbar = $('.navbar');
		      $(window).scroll(function(){
		        if($(window).scrollTop() <=40){
		          navbar.removeClass('navbar-scroll');
		        }else{
		          navbar.addClass('navbar-scroll');
		        }
		      });
		    });
	  	</script>  --}}

</body>
</html>