$(document).ready(function(){
	$('div.reserve').hide();
});
$('a.reserve').click(function(){
	$(this).siblings('div.reserve').fadeIn(500);
	return false;
});
$('div.reserve button').click(function(){
	$(this).parents('div.reserve').fadeOut(250);
	return false;
});
$("body").keydown(function(event){
	if(event.keyCode == "27"){
		$("div.reserve").fadeOut(250);
	};
});
$('form').submit(function(){
/* 	slabá ochrana před spamem, esli robot automaticky vyplní i toto pole s display:none, nedostane se dál; prozatím snad bude stačit  */
	var zk = $('.web').val();
	if(zk == ''){
		$(this).attr('action','akce/rozervi.php');
	};
	/* 	neprázdná zpráva  */
	if($(this).children('.meno').val() == ''){
		alert('Napište jméno, prosím.');
		return false;
	}
/* 	platný emajl  */
/*	var emil = $(this).children('.kdo').val();
	var emajl = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	if(!emajl.test(emil)||emil == ''){
		alert('Tato emailová adresa se jeví jako neplatná:\n\''+emil+'\'');
		return false;
	}*/
});
