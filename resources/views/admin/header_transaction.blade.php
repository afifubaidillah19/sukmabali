<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .custom-switch{
            float: right;
        }
    </style>
</head>
<body>
<div class="custom-control custom-switch">
  <input type="checkbox" class="custom-control-input" id="customSwitch1" <?php if($bupda['auto_verification_order'] == 1) echo 'checked'; else echo ''; ?>>
  <label class="custom-control-label" for="customSwitch1">Centang jika ingin transaksi di set menjadi auto terverifikasi oleh BUPDA anda.</label>
</div>
</body>
<script>
    let bupda = @json($bupda);
    
    $('#customSwitch1').on('click', function(e){
        let checked = 0;
        if(e.target.checked){
            checked = 1;
        }
        console.log("checked: "+checked);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{url('/set_auto_verification_order')}}",
            type: "post",
            data: {id: bupda['id'], checked: checked},
            success: function(data){
                console.log(data);
                if(data == 1){
                    if(checked == 0){
                        alert('Auto Verifikasi Transaksi Tidak Aktif');
                    }else {
                        alert('Auto Verifikasi Transaksi Aktif');
                    }
                }else {
                    alert('Gagal, silahkan cek koneksi internet anda');
                }
            },
            error: function(err){
                alert('Gagal, silahkan cek koneksi internet anda');
            }
        })
    });
</script>
</html>