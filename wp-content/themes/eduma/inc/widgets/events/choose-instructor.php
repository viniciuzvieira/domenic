

<style>
.dropdown-menu{
  overflow-y: scroll;
    max-height: 300px;
}
.twitter-typeahead{
	width:100%;
}
.tt-menu {
  min-width: 160px;
  margin-top: 2px;
  padding: 5px 0;
  background-color: #fff;
  border: 1px solid #ccc;
  border: 1px solid rgba(0,0,0,.2);
  *border-right-width: 2px;
  *border-bottom-width: 2px;
  -webkit-border-radius: 6px;
     -moz-border-radius: 6px;
          border-radius: 6px;
  -webkit-box-shadow: 0 5px 10px rgba(0,0,0,.2);
     -moz-box-shadow: 0 5px 10px rgba(0,0,0,.2);
          box-shadow: 0 5px 10px rgba(0,0,0,.2);
  -webkit-background-clip: padding-box;
     -moz-background-clip: padding;
          background-clip: padding-box;
}

.tt-suggestion {
  display: block;
  padding: 3px 20px;
}

.tt-suggestion.tt-is-under-cursor {
  color: #fff;
  background-color: #0081c2;
  background-image: -moz-linear-gradient(top, #0088cc, #0077b3);
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#0088cc), to(#0077b3));
  background-image: -webkit-linear-gradient(top, #0088cc, #0077b3);
  background-image: -o-linear-gradient(top, #0088cc, #0077b3);
  background-image: linear-gradient(to bottom, #0088cc, #0077b3);
  background-repeat: repeat-x;
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff0088cc', endColorstr='#ff0077b3', GradientType=0)
}

.tt-suggestion.tt-is-under-cursor a {
  color: #fff;
}

.tt-suggestion p {
  margin: 0;
}

body{
  margin-left: 50px;
  margin-top: 60px;
}
</style>

<script>
var industries = new Array();
	
	$(".dropdown-menu li a[data-drop='industry']").each(function(index) {
		var element = $(this);
		
        industries.push(element.attr("data-name"));
    });
	
	$(document).on("click", ".dropdown-menu input", function(){
		return false;
	});
	
	var substringMatcher = function(strs) {
  return function findMatches(q, cb) {
    var matches, substringRegex;

    // an array that will be populated with substring matches
    matches = [];

    // regex used to determine if a string contains the substring `q`
    substrRegex = new RegExp(q, 'i');

    // iterate through the pool of strings and for any string that
    // contains the substring `q`, add it to the `matches` array
    $.each(strs, function(i, str) {
      if (substrRegex.test(str)) {
        matches.push(str);
      }
    });

    cb(matches);
  };
};
	
	$('.typeahead').typeahead({
  hint: true,
  highlight: true,
  minLength: 1
},
{
  name: 'industries',
  source: substringMatcher(industries)
});
</script>



<div id ="thim-popup-login" class="dropdown">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> Dropdown <span class="caret"></span> </button>
  <ul class="dropdown-menu dropdown-menu-full" aria-labelledby="dropdown-industry" style="overflow-x: hidden;">
    <li>
      <input type="text" class="typeahead form-control" placeholder="Type industry name">
    </li>
    <li><a href="#" data-drop="industry" data-id="109" data-name="Automotive Services - (Automotive)">Automotive Services </a></li>
    <li><a href="#" data-drop="industry" data-id="106" data-name="Car Dealers - (Automotive)">Car Dealers </a></li>
    <li><a href="#" data-drop="industry" data-id="112" data-name="Cars - (Automotive)">Cars </a></li>
    <li><a href="#" data-drop="industry" data-id="113" data-name="Motorcycles - (Automotive)">Motorcycles </a></li>
    <li><a href="#" data-drop="industry" data-id="114" data-name="Trucks - (Automotive)">Trucks </a></li>
  </ul>
</div>