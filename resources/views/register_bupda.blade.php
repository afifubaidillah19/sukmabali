<!DOCTYPE html>

<html>

<head>

  <title></title>

  <meta charset="utf-8">

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

  <link rel="stylesheet" type="text/css" href="{{asset('css/font.css')}}">

  <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

  <style type="text/css">

    #map {

      width: 100%;

      height: 300px;

    }

  </style>

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

              <a class="nav-link" href="#">Download</a>

            </li>

          </ul>

        </div>

      </nav>



      <div class="container">

                <div class="title-section">

                    <h2>Daftar Bupda</h2>

                </div>

                @if (Session::has('message'))

                  <div class="alert alert-danger">{{ Session::get('message') }}</div>

                @endif

                <div class="row d-flex justify-content-center form-bupda">

                    <form class="col-md-6" action="{{url('register/store')}}" method="POST" enctype='multipart/form-data'>

                    <input type="hidden" value="{{ csrf_token() }}" name="_token">

                        <div class="form-group">

                            <span>Nama Bupda</span>

                            <input required type="text" name="bupda_name" placeholder="Nama Bupda" class="form-control" value="{{ old('bupda_name') }}" id="bupda_name">

                        </div>

                        <div class="form-group">

                            <span>Nomor Handphone</span>

                            <input required type="number" name="phone_number" placeholder="Nomor Handphone" class="form-control" value="{{ old('phone_number') }}" id="phone_number">

                        </div>
                        <div class="form-group">

                          <span>Kabupaten</span>

                          <select id="kabupaten_id" name="kabupaten_id" required class="form-control">
                              <option value="">Kabupaten</option>
                          </select>

                        </div>
                        <div class="form-group">

                            <span>Kecamatan</span>

                            <select id="kecamatan_id" name="kecamatan_id" required class="form-control">
                              <option value="">Kecamatan</option>
            
                            </select>

                        </div>
                        <div class="form-group">

                            <span>Desa Adat</span>

                            <select id="desa_adat_id" name="desa_adat_id" required class="form-control">

                              <option value="">Desa Adat</option>

                            </select>

                        </div>

                        <div class="form-group" id="temp_desa_adat" style="display: none">

                            <span>Nama Desa Adat</span>

                            <input type="text" name="temp_desa_adat" placeholder="Masukkan nama desa adat" class="form-control">

                        </div>

                        <div class="form-group">

                            <span>Pilih Lokasi Menggunakan Map Berikut</span>

                            <div id="map"></div>

                            <input id="confirmPosition" type="button" value="Pilih Lokasi" class="form-control btn-danger" style="margin-top: 10px"/>

                            <span>Latitude</span>

                            <input id="latitude" readonly required name="latitude" class="form-control" value="0" />

                            <span>Longitude</span>

                            <input id="longitude" readonly required name="longitude" class="form-control" value="0" />

                            <span>Alamat</span>

                            <input required type="text" name="address" id="address" class="form-control">

                            <small class="text-danger">* Klik tombol "Pilih Lokasi" untuk memilih lokasi BUPDA</small>

                        </div>

                        

                        <div class="form-group">

                            <span>Deskripsi</span>

                            <input required type="text" name="description" placeholder="Deskripsi" class="form-control" value="{{ old('description') }}" id="description">

                        </div>

                        <!-- <div class="form-group">

                            <span>Foto KTP</span>

                            <input required type="file" name="ktp" placeholder="Foto KTP" class="form-control">

                        </div>

                        <div class="form-group">

                            <span>Foto Bupda</span>

                            <input required type="file" name="bupda_photo" placeholder="Foto Bupda" class="form-control">

                        </div> -->

                        <!-- <div class="form-group">

                            <span>No Rekening</span>

                            <input required type="number" name="no_rekening" placeholder="No Rekening" class="form-control">

                        </div>
                        <div class="form-group">

                            <span>Nama Bank</span>

                            <input required type="text" name="nama_bank" placeholder="Nama Bank" class="form-control">

                        </div>
                        <div class="form-group">

                            <span>Nama Pemilik Rekening</span>

                            <input required type="text" name="an" placeholder="Nama Pemilik Rekening" class="form-control">

                        </div> -->
                        <div class="form-group">

                            <span>Password</span>

                            <input required type="password" name="password" placeholder="Password" class="form-control">
                            <small class="text-danger">* Password minimal 6 karakter</small>
                        </div>

                        <div class="form-group">

                            <span>Konfirmasi Password</span>

                            <input required type="password" name="konfirmasi_password" placeholder="Konfirmasi Password" class="form-control">

                        </div>

                        <div class="form-group">

                            <input required type="submit" class="form-control btn btn-primary" value="Daftar">

                        </div>

                    </form>

            </div>

            </div>

    </div>

  </div>



</body>

</html>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBZglzXqprC3gUfqobzRZB5H91XcdZf4Pc"></script>

<script src="https://unpkg.com/location-picker/dist/location-picker.min.js"></script>

<script>

// Get element references

var confirmBtn = document.getElementById('confirmPosition');

var onClickPositionView = document.getElementById('onClickPositionView');

var resultLatitude = document.getElementById('latitude');

var resultLongitude = document.getElementById('longitude');

var resultAddress = document.getElementById('address');

// Initialize locationPicker plugin

var lp = new locationPicker('map', {

  setCurrentPosition: true, // You can omit this, defaults to true

}, {

  zoom: 8, // You can set any google map options here, zoom defaults to 15
  center:{lat:-8.409518, lng:115.188919}

});



// Listen to button onclick event

confirmBtn.onclick = function () {

  // Get current location and show it in HTML

  var location = lp.getMarkerPosition();

  getUserAddressBy(location.lat, location.lng);

  

  resultLatitude.value = location.lat;

  resultLongitude.value = location.lng;

};



// Listen to map idle event, listening to idle event more accurate than listening to ondrag event

google.maps.event.addListener(lp.map, 'idle', function (event) {

  // Get current location and show it in HTML

  var location = lp.getMarkerPosition();

});

function getUserAddressBy(lat, long) {

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function () {

        if (this.readyState == 4 && this.status == 200) {

            var address = JSON.parse(this.responseText)

            resultAddress.value = address.results[0].formatted_address;

            console.log(address.results[0].formatted_address);

        }

    };

    xhttp.open("GET", "https://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+","+long+"&key=AIzaSyBZglzXqprC3gUfqobzRZB5H91XcdZf4Pc", true);

    xhttp.send();

}


let desaAdat = @json($desaAdat);
let kabupaten = @json($kabupaten);
let kecamatan = @json($kecamatan);

$('#desa_adat_id').on('change',function(e){
  let id = e.target.value;
  console.log(id);
})

$('#kabupaten_id').on('change', function(e){
  $('#kecamatan_id').children('option').remove();
  let id = e.target.value;
  let kec = getKecamatanByKabupaten(id);
  $.each(kec, function(key, value){
    $('#kecamatan_id')
        .append($('<option></option')
        .attr('value', value.id)
        .text(value.nama));
  });
  
  $('#desa_adat_id').children('option').remove();
  let kec_id = kec[0]['id'];
  let ds = getDesaAdatByKecamatan(kec_id);
    $.each(ds, function(key, value){
      $('#desa_adat_id')
          .append($('<option></option')
          .attr('value', value.id)
          .text(value.nama));
    });
});

$('#kecamatan_id').on('change', function(e){
  $('#desa_adat_id').children('option').remove();
  let id = e.target.value;
  let ds = getDesaAdatByKecamatan(id);
    $.each(ds, function(key, value){
      $('#desa_adat_id')
          .append($('<option></option')
          .attr('value', value.id)
          .text(value.nama));
    });
});

$( document ).ready(function() {
    let kabupaten_id = kabupaten[0]['id'];
  
    $.each(kabupaten, function(key, value){
      $('#kabupaten_id')
          .append($('<option></option>')
          .attr("value", value.id)
          .text(value.nama));
    });

    let kec = getKecamatanByKabupaten(kabupaten_id);
    let kecamatan_id = kec[0]['id'];
    $.each(kec, function(key, value){
      $('#kecamatan_id')
          .append($('<option></option')
          .attr('value', value.id)
          .text(value.nama));
    });

    let ds = getDesaAdatByKecamatan(kecamatan_id);
    $.each(ds, function(key, value){
      $('#desa_adat_id')
          .append($('<option></option')
          .attr('value', value.id)
          .text(value.nama));
    });
});

function getKecamatanByKabupaten(kabupaten_id){
  let kec = [];
  $.each(kecamatan, function(key, value){
    if(value.kabupaten_id == kabupaten_id){
      kec.push(value);
    }
  })
  return kec;
}

function getDesaAdatByKecamatan(kecamatan_id){
  let ds = [];
  $.each(desaAdat, function(key, value){
    if(value.kecamatan_id == kecamatan_id){
      ds.push(value);
    }
  })
  return ds;
}
</script>