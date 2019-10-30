//Self-invoking function
(function()
{
  // Defining HTML elements
  var filters = document.querySelectorAll('#filters img');
  var active_filter = new Image();
  var form = document.getElementById('form');
  var photo = document.getElementById('photo');

  var display_width = document.getElementById('left').clientWidth * 0.9; // Scales the display to be responsive.
  var display_height = photo.height / (photo.width / display_width);
  var display_canvas = document.getElementById('display_canvas');
  var display_context = display_canvas.getContext('2d');
  var display_timeout;

  var real_width = 800; // Scales the elements' real size.
  var real_height = photo.height / (photo.width / real_width);
  var real_canvas = document.getElementById('real_canvas');
  var real_context = real_canvas.getContext('2d');

  // Assigning the initial dimensions
  // and the photo source to the display/form.
  display_canvas.setAttribute('width', display_width);
  display_canvas.setAttribute('height', display_height);

  real_canvas.setAttribute('width', real_width);
  real_canvas.setAttribute('height', real_height);
  form.elements['width'].value = real_width;
  form.elements['height'].value = real_height;
  form.elements['photo'].value = photo.src.replace('http://localhost:8081/repcamagru/', '');

  // Initiating the display.
  draw_display(photo, display_context, display_width, display_height);

  /* ------------- functions ------------- */

  // Draws the photo plus the active filter onto the display canvas.
  function draw_display(photo, context, width, height)
  {
    context.drawImage(photo, 0, 0, width, height);
    context.drawImage(active_filter, 0, 0, width, height);
    display_timeout = setTimeout(draw_display, 10, photo, context, width, height);
  }

  /* --------------- events --------------- */

  // On window resizing, redefines the display dimensions (responsive).
  window.addEventListener(
    'resize',
    function()
    {
      clearTimeout(display_timeout);
      display_width = document.getElementById('left').clientWidth * 0.9;
      display_height = photo.height / (photo.width / display_width);

      display_canvas.setAttribute('width', display_width);
      display_canvas.setAttribute('height', display_height);
      draw_display(photo, display_context, display_width, display_height);
    },
    false
  );

  // When an image under filters is clicked, sets it as the active filter
  // and enables the "Take photo" button
  for (var i = 0; i < filters.length; i++)
  {
   filters[i].addEventListener(
     'click',
     function(event)
     {
       active_filter.src = event.target.src;
       form.elements['filter'].value = event.target.src;
       form.elements['button'].removeAttribute('disabled');
     },
     false
   );
  }
})();
