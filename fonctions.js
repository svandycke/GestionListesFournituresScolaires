// Colore dans le menu la page active
function menu_active(id){
	var selectionne = document.getElementById(id);
	selectionne.style.backgroundColor = "#D3D3D3";
}

// Affiche ou Masque le bloc ajouter
function afficher_masquer_bloc_ajouter(id){
	var bloc_ajouter = document.getElementById(id);
	var etat_bloc_ajouter = bloc_ajouter.style.display;
	
	if(etat_bloc_ajouter === 'block'){
		bloc_ajouter.style.display = "none";	
	}
	else{
		bloc_ajouter.style.display = "block";	
	}
}

// Alterne la couleur des lignes dans un tableau
function AlternerCouleur(tableau){
	var NbLignes = tableau.getElementsByTagName('tr').length;
	var Lignes = new Array();
	Lignes = tableau.getElementsByTagName('tr');
	for(var i = 0 ; i < NbLignes ; i++){
		if((i-1)%2 == 0){
			Lignes[i].style.backgroundColor = '#FFFFFF';
		}
		else{
			Lignes[i].style.backgroundColor = '#EEEEEE';
		}
	}
}

// Lance la mise à jour de la quantité de la fourniture
function maj_quantite(id){	
	document.getElementById(id).submit();
}

// Fonctions qui tri un tableau
function twInitTableau() {
       [].forEach.call( document.getElementsByClassName("avectri"), function(oTableau) {
       var oEntete = oTableau.getElementsByTagName("tr")[0];
       var nI = 1;
      [].forEach.call( oEntete.querySelectorAll("th"), function(oTh) {
        oTh.addEventListener("click", twTriTableau, false);
        oTh.setAttribute("data-pos", nI);
        if(oTh.getAttribute("data-tri")=="1") {
         oTh.innerHTML += "<span class=\"flecheDesc\"></span>";
        } else {
          oTh.setAttribute("data-tri", "0");
          oTh.innerHTML += "<span class=\"flecheAsc\"></span>";
        }
        if (oTh.className=="selection") {
          oTh.click();
        }
        nI++;
      });
    });
  }
  
  function twInit() {
    twInitTableau();
  }
  function twPret(maFonction) {
    if (document.readyState != "loading"){
      maFonction();
    } else {
      document.addEventListener("DOMContentLoaded", maFonction);
    }
  }
  twPret(twInit);

  function twTriTableau() {
    var nBoolDir = this.getAttribute("data-tri");
    this.setAttribute("data-tri", (nBoolDir=="0") ? "1" : "0");
    [].forEach.call( this.parentNode.querySelectorAll("th"), function(oTh) {oTh.classList.remove("selection");});
    this.className = "selection";
    this.querySelector("span").className = (nBoolDir=="0") ? "flecheAsc" : "flecheDesc";

    var oTbody = this.parentNode.parentNode.parentNode.getElementsByTagName("tbody")[0]; 
    var oLigne = oTbody.rows;
    var nNbrLigne = oLigne.length;
    var aColonne = new Array(), i, j, oCel;
    for(i = 0; i < nNbrLigne; i++) {
      oCel = oLigne[i].cells;
      aColonne[i] = new Array();
      for(j = 0; j < oCel.length; j++){
        aColonne[i][j] = oCel[j].innerHTML;
      }
    }

    var nIndex = this.getAttribute("data-pos");
    var sFonctionTri = (this.getAttribute("data-type")=="num") ? "compareNombres" : "compareLocale";
    aColonne.sort(eval(sFonctionTri));
    function compareNombres(a, b) {return a[nIndex-1] - b[nIndex-1];}
    function compareLocale(a, b) {return a[nIndex-1].localeCompare(b[nIndex-1]);}
    if (nBoolDir==0) aColonne.reverse();
    
    for(i = 0; i < nNbrLigne; i++){
      aColonne[i] = "<td>"+aColonne[i].join("</td><td>")+"</td>";
    }
    oTbody.innerHTML = "<tr>"+aColonne.join("</tr><tr>")+"</tr>";
  }
  
  // Fonction qui selectionne toutes les valeurs d'un select et soumet le formulaire
function PostSelect(formulaire, select1, select2)
{
	var sel1 = formulaire.elements[select1];
	var sel2 = formulaire.elements[select2];

	// On compte le nombre d'item de la liste select
	var NbSel1 = sel1.length;
	var NbSel2 = sel2.length;

	// On lance une boucle pour selectionner tous les items
	for (var a = 0; a < NbSel1; a++)
	{
		sel1.options[a].selected = "selected";
	}
	
	for (var a = 0; a < NbSel2; a++)
	{
		sel2.options[a].selected = "selected";
	}

	// On soumet le formulaire
	formulaire.submit();
}

// Passer des élèments d'un select à l'autre
function SelectMoveRows(SS1,SS2)
{
    var SelID='';
    var SelText='';
    for (i=SS1.options.length - 1; i>=0; i--)
    {
        if (SS1.options[i].selected == true)
        {
            SelID=SS1.options[i].value;
            SelText=SS1.options[i].text;
            var newRow = new Option(SelText,SelID);
			newRow.classList.add('select_class');
            SS2.options[SS2.length]=newRow;
            SS1.options[i]=null;
	        }
    }
    SelectSort(SS2);
}
function SelectSort(SelList)
{
    var ID='';
    var Text='';
    for (x=0; x < SelList.length - 1; x++)
    {
        for (y=x + 1; y < SelList.length; y++)
        {
            if (SelList[x].text > SelList[y].text)
            {
                // Swap rows
                ID=SelList[x].value;
                Text=SelList[x].text;
                SelList[x].value=SelList[y].value;
                SelList[x].text=SelList[y].text;
	
                SelList[y].value=ID;
                SelList[y].text=Text;
            }
        }
    }
}
  
