
var laDiv = document.getElementById("myDiv_side");
var add_directoryBtn = document.getElementById('ajout');
function getId(element) {
  if (element.id == "ajout") {
    document.getElementById('name_directory').style.visibility = "visible";
  } else {
    document.getElementById('name_directory').style.visibility = "hidden";
  }
  if (element.id == "up_fichier") {
    document.getElementById('upload_').style.visibility = "visible";
  } else {
    document.getElementById('upload_').style.visibility = "hidden";
  }
}
function getFichier(element){
 console.log(element.id);
}

function theCheckbox(element) {
  var the_id = element.getAttribute('id');
  var btn = document.getElementById(the_id);
  var un_fichier = "";
 
  if (btn.checked) {
    console.log(the_id);
    var you = document.querySelector("#"+the_id);
    console.log(you);
    un_fichier = document.getElementsByClassName(the_id + "_checkbox");
    un_fichier[0].style.background = "yellow";
    document.getElementsByName("acc")[0].value
  } else if (!btn.checked) {
   
    un_fichier = document.getElementsByClassName(the_id + "_checkbox");
    un_fichier[0].style.background = "";

  }
}
//le button listes pour afficher la sidebar gestion des couleurs
var btn_liste = document.getElementById("menu-toggle");
btn_liste.addEventListener('click', changeIt);
function changeIt() {
  var btn_liste = document.getElementById("menu-toggle");
  btn_liste.classList.remove('active');
  btn_liste.classList.toggle("addColor");
}
//new 
//recupérations du dossier pour changer la couleur
var currentUrl = window.location.href;

if(currentUrl.includes('page=')){
  if(currentUrl.includes('&')){
    var newUrl = currentUrl.substring(
      currentUrl.lastIndexOf("?"), 
      currentUrl.lastIndexOf("&")
    );
    teema = newUrl.split('page=').pop();
    var active_directory = document.getElementById(teema);
    active_directory.style.backgroundColor = "yellow";
  }else{
    teema = currentUrl.split('page=').pop();
    var active_directory = document.getElementById(teema);
    active_directory.style.backgroundColor = "yellow";
  }
}
// la barre de recherche
    $(document).ready(function(){

      $("#recherche_fichier").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#tableCorps tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });

    });

// editable colonne

const $tableID = $('#this_table');
 const $BTN = $('#export-btn');
 const $EXPORT = $('#export');

 const newTr = `
<tr class="hide">
  <td class="pt-3-half" contenteditable="true">Example</td>
  <td class="pt-3-half" contenteditable="true">Example</td>
  <td class="pt-3-half" contenteditable="true">Example</td>
  <td class="pt-3-half" contenteditable="true">Example</td>
  <td class="pt-3-half" contenteditable="true">Example</td>
  <td class="pt-3-half">
    <span class="table-up"><a href="#!" class="indigo-text"><i class="fas fa-long-arrow-alt-up" aria-hidden="true"></i></a></span>
    <span class="table-down"><a href="#!" class="indigo-text"><i class="fas fa-long-arrow-alt-down" aria-hidden="true"></i></a></span>
  </td>
  <td>
    <span class="table-remove"><button type="button" class="btn btn-danger btn-rounded btn-sm my-0 waves-effect waves-light">Remove</button></span>
  </td>
</tr>`;

 $('.table-add').on('click', 'i', () => {

   const $clone = $tableID.find('tbody tr').last().clone(true).removeClass('hide table-line');

   if ($tableID.find('tbody tr').length === 0) {

     $('tbody').append(newTr);
   }

   $tableID.find('table').append($clone);
 });

 $tableID.on('click', '.table-remove', function () {

   $(this).parents('tr').detach();
 });

 $tableID.on('click', '.table-up', function () {

   const $row = $(this).parents('tr');

   if ($row.index() === 0) {
     return;
   }

   $row.prev().before($row.get(0));
 });

 $tableID.on('click', '.table-down', function () {

   const $row = $(this).parents('tr');
   $row.next().after($row.get(0));
 });

 // A few jQuery helpers for exporting only
 jQuery.fn.pop = [].pop;
 jQuery.fn.shift = [].shift;

 $BTN.on('click', () => {

   const $rows = $tableID.find('tr:not(:hidden)');
   const headers = [];
   const data = [];

   // Get the headers (add special header logic here)
   $($rows.shift()).find('th:not(:empty)').each(function () {

     headers.push($(this).text().toLowerCase());
   });

   // Turn all existing rows into a loopable array
   $rows.each(function () {
     const $td = $(this).find('td');
     const h = {};

     // Use the headers from earlier to name our hash keys
     headers.forEach((header, i) => {

       h[header] = $td.eq(i).text();
     });

     data.push(h);
   });

   // Output the result
   $EXPORT.text(JSON.stringify(data));
 });
