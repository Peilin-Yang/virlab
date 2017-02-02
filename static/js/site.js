function equalHeight(group) {      
    group.each(function() { $(this).height(160); });
} 

$(document).ready(function() {
  $('.flexslider').flexslider({
    animation: "slide"
  });

  equalHeight($(".thumbnail"));
});