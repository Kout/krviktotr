$('#bok a').click(function(){
	$('#bok a').removeClass('tu');
	$(this).addClass('tu');
	var cesta = $(this).attr('data-nudle');
	if(cesta){
		var preobr = $(this).attr('href').replace('#','');
		$('#hlava > img').attr('src',cesta+'/'+preobr+'.jpg');
	}
});