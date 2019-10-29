//Self-invoking function
(function()
{
  // Defining HTML elements
  var photo = document.getElementById('photo');
  var width = photo.width;
  var height = photo.height;

  var canvas_preview = document.getElementById('canvas_preview');
  var context_preview = canvas_preview.getContext('2d');

  var filters = document.querySelectorAll('#filters img');
  var active_filter = new Image();

  var form = document.getElementById('form');

  /* ------------- functions ------------- */

  // Draws the content of the photo and the filter space onto the canvas.
  function draw_preview(photo, context_preview, width, height)
  {
    context_preview.drawImage(photo, 0, 0, width, height);
    context_preview.drawImage(active_filter, 0, 0, width, height);
    setTimeout(draw_preview, 10, photo, context_preview, width, height);
  }

  /* --------------- events --------------- */

  // When function is loaded (not really an event),
  // defines the dimensions of the canvases according to the photo,
  // then initiates the preview.
  canvas_preview.setAttribute('width', width);
  canvas_preview.setAttribute('height', height);
  form.elements['photo'].value = photo.src;
  form.elements['width'].value = width;
  form.elements['height'].value = height;
  draw_preview(photo, context_preview, width, height);

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
