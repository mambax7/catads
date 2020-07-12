/******************************************
# Auteur : Julien Theler - www.twiip.ch
# Contact : julien.theler@twiip.ch
# Licence : CC-by-nc
******************************************/

function infobulle(element, text){
	var is_ie = ((navigator.userAgent.toLowerCase().indexOf("msie") != -1) && (navigator.userAgent.toLowerCase().indexOf("opera") == -1));
	
	//Suppression du title de l'élément pour éviter une superposition
	element.title = '';
	
	//Création d'une div provisoire
	var infobulle = document.createElement('div');
	infobulle.innerHTML = text;
	infobulle.id = 'infobulle';
	infobulle.style.display = 'none';
	infobulle.style.opacity = '0';
	infobulle.style.filter = 'alpha(opacity=0)';
	document.body.appendChild(infobulle);
	
	infobulle.style.position = 'absolute';
	document.onmousemove = function(e){
		x = (!is_ie ? e.pageX : event.x+document.documentElement.scrollLeft)+15;
		y = (!is_ie ? e.pageY : event.y+document.documentElement.scrollTop)+15;
		
		var windowWidth = (!is_ie ? window.innerWidth : document.documentElement.clientWidth);
		var windowHeight = (!is_ie ? window.innerHeight : document.documentElement.clientHeight);
		var scrollLeft = document.documentElement.scrollLeft;
		var scrollTop = document.documentElement.scrollTop;
		
		//Calcul des dimensions de l'infobulle
		infobulle.style.display = '';
		var infoWidth = infobulle.offsetWidth;
		var infoHeight = infobulle.offsetHeight;
		infobulle.style.display = 'none';
		
		/*On vérifie que l'infobulle ne sorte pas de la fenêtre*/
		if((x+infoWidth) > (windowWidth+scrollLeft)){
			x = (!is_ie ? e.pageX : event.x+document.documentElement.scrollLeft)-infoWidth-5;
		}
		if((y+infoHeight) > (windowHeight+scrollTop)){
			y = (!is_ie ? e.pageY : event.y+document.documentElement.scrollTop)-infoHeight-5;
		}
		
		infobulle.style.left = x+'px';
		infobulle.style.top = y+'px';
		infobulle.style.display = '';
	}

	for(i=0; i<=100; i+=10){
		var time = ((i/20)*30);
		setTimeout('opacity('+i+', \'infobulle\');', time);
	}


	//Ajout de la fermeture lorsque la souris quitte l'élément
	element.onmouseout = function(){
		for(i=0; i<=100; i+=10){
			var time = ((i/20)*30);
			var opacity = (100-i);
			setTimeout('opacity('+opacity+', \'infobulle\', 1);', time);
		}
	};
	
	//Fonction servant à faire varier l'opacité
	opacity = function(opacity, id, close){
			var infobulle = document.getElementById(id);
			infobulle.style.opacity = (opacity/100);
			infobulle.style.filter = 'alpha(opacity='+opacity+')';
			if(opacity == 0 && close){
				document.body.removeChild(infobulle); //Suppression de la div provisoire
				document.onmousemove = '';
			}
	}
}