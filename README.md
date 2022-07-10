# Spotify-Premium-Mini

Ambil cookie sama csrf pake chrome '?clientName=premium-www-checkout&clientContext=premium-checkout&version=4.8.3', pas mencet lanjutkan pembayaran langsung stop proses browser aja biar ga reload
Ganti di config.php
PIN Gopay juga ganti di config.php
getqr.php fungsinya buat get link trus auto scan
autopay.php fungsinya buat auto paynya
login.php fungsinya buat get token gojek kalian, Pake unverif aja 
logicnya
getqr sebanyak banyaknya
trus jalanin autopay kalau link sudah dirasa cukup
NOTE : Link jangan sampai lebih dari 15 menit lebih dari itu link expired
jadi tips dari gw get link 10 menit, trus langsung run autopaynya
