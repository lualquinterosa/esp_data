  
        <!-- css table datatables/dataTables -->
		<!-- <link rel="stylesheet" href="../datatables/dataTables.bootstrap.css"/> -->
    <!-- CSS only -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script></head> -->
        <!-- <link type="text/css" href="../images/icons/css/font-awesome.css" rel="stylesheet"> -->
        <link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600'    rel='stylesheet'>
            
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

   
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

<script>


function confirm(tit,cont,href){
$.confirm({
    title: tit,
    content: cont,
    buttons: {
        confirm: function () {
       location.href = href;
	return true;
        },
        cancel: function () {
           return true;
        }
    }
});	
}
function alert(tit,cont,href){
	
$.alert({
    title: tit,
    content: cont,
buttons: {
        Ok: function () {
       if (href !="") location.href = href;
	return true;
        }
        }
});
	
}
function alert2(tit,cont,href){
	location.href = href;

}
function alert_normal(tit,cont){
	
$.alert({
    title: tit,
    content: cont,
buttons: {
        Ok: function () {
      // if (href !="") location.href = href;
	return true;
        }
        }
});
	
}
</script>
<style>
body, html{ margin:0; padding:0;}
.color_libre{ background-color: #A4F9E0 }
.color_debe{ background-color: #F7B863 }
.color_abono{ background-color: #F5F285 }
.color_pago{ background-color: #9DF99A }


</style>