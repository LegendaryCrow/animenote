/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(e) {
  if (!e.target.matches('.dropbtn')) {

    var dropdowns = document.getElementsByClassName("dropdown-content");
    for (var d = 0; d < dropdowns.length; d++) {
      var openDropdown = dropdowns[d];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}

var last_tab = 1;

function loadTab(tab_number)
{
	if (tab_number === last_tab) return;
	
	document.getElementById("tab" + last_tab).style.display = "none";
	document.getElementById("tab" + tab_number).style.display = "block";
	document.getElementById("tab-button" + last_tab).style.opacity = "0.5";
	document.getElementById("tab-button" + tab_number).style.opacity = "1.0";

	last_tab = tab_number;
}

$(document).ready(function(){
    
    //Check to see if the window is top if not then display button
    $(window).scroll(function(){
        if ($(this).scrollTop() > 100) {
            $('.scrollToTop').fadeIn();
        } else {
            $('.scrollToTop').fadeOut();
        }
    });
    
    //Click event to scroll to top
    $('.scrollToTop').click(function(){
        $('html, body').animate({scrollTop : 0},800);
        return false;
    });
    
});

// Popup
    ;(function($) {
        $(function() {
            $('#my-button').bind('click', function(e) {
                e.preventDefault();
                $('#element_to_pop_up').bPopup();
            });
        });
    })(jQuery);

function procurar_opcoes() {
    var x = document.getElementById('procura-avancadas');
    if (x.style.display === 'none') {
        x.style.display = 'block';
    } else {
        x.style.display = 'none';
    }
}

function check(estado, total_ep) {
	if(estado==1){
		document.getElementById("episodios_vistos").value = total_ep;
	}
}

function ts(cb) {
	if (cb.name=="ungenero[]"){cb.name="genero[]";}
	else if(!cb.checked){cb.checked=cb.readOnly=true;cb.name="ungenero[]";}
}

function uncheckAll(){
	$('#selectBox :nth-child(1)').prop('selected', true);
    $('input[type="checkbox"]:checked').prop('checked',false);
    $('input[readonly=""][type="checkbox"]').prop('checked',false).prop('readonly',false);
}

function deleteRow(btn) {
  var row = btn.parentNode.parentNode;
  row.parentNode.removeChild(row);
}

function bs(cb) {
	if(!cb.checked || cb.checked=="true"){cb.checked=false;}else{cb.checked=true;}
}

//Double click editable
		$(function () {
            //Loop through all Labels with class 'editable'.
            $(".editable").each(function () {
                //Reference the Label.
                var label = $(this);
				var name = $("#titulo").attr("name");

                //Add a TextBox next to the Label.
                label.after("<input type = 'text' style = 'display:none; width: 100%;' name='"+name+"' />");

                //Reference the TextBox.
                var textbox = $(this).next();
				
                //Set the name attribute of the TextBox.
                textbox[0].name = this.id.replace("lbl", "txt");

                //Assign the value of Label to TextBox.
                textbox.val(label.html());
				
                //When Label is clicked, hide Label and show TextBox.
                label.click(function () {
                    $(this).hide();
                    $(this).next().show();
                    $(this).next().focus();
                });

                //When focus is lost from TextBox, hide TextBox and show Label.
                textbox.focusout(function () {
                    $(this).hide();
                    $(this).prev().html($(this).val());
                    $(this).prev().show();
                });
            });
        });
		$(function () {
            //Loop through all Labels with class 'editable'.
            $(".editable1").each(function () {
                //Reference the Label.
                var label = $(this);
				var name = $("#titulo").attr("name");

                //Add a TextBox next to the Label.
                label.after("<textarea style = 'display:none' name='"+name+"' />");

                //Reference the TextBox.
                var textbox = $(this).next();

                //Set the name attribute of the TextBox.
                textbox[0].name = this.id.replace("lbl", "txt");

                //Assign the value of Label to TextBox.
                textbox.val(label.html());

                //When Label is clicked, hide Label and show TextBox.
                label.click(function () {
                    $(this).hide();
                    $(this).next().show();
                    $(this).next().focus();
                });

                //When focus is lost from TextBox, hide TextBox and show Label.
                textbox.focusout(function () {
                    $(this).hide();
                    $(this).prev().html($(this).val());
                    $(this).prev().show();
                });
            });
        });

		function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                };

                reader.readAsDataURL(input.files[0]);
            }
        }