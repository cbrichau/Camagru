//Self-invoking function
(function()
{
  // Defining HTML elements
  var filters = document.querySelectorAll('#filters img');
  var active_filter = new Image();
  var form = document.getElementById('form');
  var video = document.getElementById('video');

  var display_width = document.getElementById('left').clientWidth * 0.9; // Scales the display to be responsive.
  var display_height = display_width / (4/3);
  var display_canvas = document.getElementById('display_canvas');
  var display_context = display_canvas.getContext('2d');
  var display_timeout;

  var real_width = 800; // Scales the elements' real size.
  var real_height = real_width / (4/3);
  var real_canvas = document.getElementById('real_canvas');
  var real_context = real_canvas.getContext('2d');

  // Assigning the real dimensions to the hidden elements
  // (as opposed to display dimensions for responsive behaviour).
  real_canvas.setAttribute('width', real_width);
  real_canvas.setAttribute('height', real_height);
  form.elements['width'].value = real_width;
  form.elements['height'].value = real_height;

  // Ensuring getUserMedia() compatibility
  navigator.getMedia = navigator.getUserMedia ||
                       navigator.webkitGetUserMedia ||
                       navigator.mozGetUserMedia ||
                       navigator.msGetUserMedia;

  // Defining getUserMedia's 3 mandatory parameters:
  // what stream take, what to do with that stream, and what to do on error.
  navigator.getMedia(
    {
      video: true,
      audio: false
    },
    function(stream)
    {
      video.srcObject = stream;
      video.play();
    },
    function(error)
    {}
  );

  /* ------------- functions ------------- */

  // Draws the video stream plus the active filter onto the display canvas.
  function draw_display(video, context, width, height)
  {
    context.drawImage(video, 0, 0, width, height);
    context.drawImage(active_filter, 0, 0, width, height);
    display_timeout = setTimeout(draw_display, 10, video, context, width, height);
  }

  /* --------------- events --------------- */

  // On 'play', defines the dimensions of the display elements
  // and initiates that display.
  video.addEventListener(
    'play',
    function()
    {
      video.setAttribute('width', display_width);
      video.setAttribute('height', display_height);
      display_canvas.setAttribute('width', display_width);
      display_canvas.setAttribute('height', display_height);
      draw_display(this, display_context, display_width, display_height);
    },
    false
  );

  // On window resizing, redefines the display dimensions (responsive).
  window.addEventListener(
    'resize',
    function()
    {
      video.pause();
      clearTimeout(display_timeout);
      display_width = document.getElementById('left').clientWidth * 0.9;
      display_height = display_width / (4/3);
      video.play();
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

  // When form is submitted (i.e. user clicked "Take photo"),
  // draws the photo onto the real-sized canvas and sends the form.
  form.addEventListener(
    'submit',
    function(event)
    {
      event.preventDefault();
      real_context.drawImage(video, 0, 0, real_width, real_height);
      form.elements['photo'].value = real_canvas.toDataURL('image/png');
      form.submit();
    },
    false
  );
})();
