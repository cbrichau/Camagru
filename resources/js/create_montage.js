//Self-invoking function
(function()
{
  // Defining HTML elements
  var video = document.getElementById('video');
  var width = document.getElementById('left').clientWidth * 0.9;  // Scales the video width to this.
  var height = 0; // Computed later based on the video stream.

  var canvas_preview = document.getElementById('canvas_preview');
  var context_preview = canvas_preview.getContext('2d');

  var canvas_photo = document.getElementById('canvas_photo');
  var context_photo = canvas_photo.getContext('2d');

  var filters = document.getElementById('filters');
  var active_filter = new Image();

  var form = document.getElementById('form');

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
    {
      //error.code
    }
  );

  /* ------------- functions ------------- */

  // Draws the content of the video stream and the filter space onto the canvas.
  function draw_preview(video, context_preview, width, height)
  {
    context_preview.drawImage(video, 0, 0, width, height);
    context_preview.drawImage(active_filter, 0, 0, width, height);
    setTimeout(draw_preview, 10, video, context_preview, width, height);
  }

  /* --------------- events --------------- */
  window.onresize = function(event)
  {
    width = document.getElementById('left').clientWidth * 0.9;
  }

  // On 'play', gets the height of the stream and defines the dimensions
  // of the canvases accordingly, then initiates the preview.
  video.addEventListener(
    'play',
    function()
    {
      height = video.videoHeight / (video.videoWidth/width);
      if (isNaN(height))
      {
        height = width / (4/3);
      }
      video.setAttribute('width', width);
      video.setAttribute('height', height);
      canvas_preview.setAttribute('width', width);
      canvas_preview.setAttribute('height', height);
      canvas_photo.setAttribute('width', width);
      canvas_photo.setAttribute('height', height);
      form.elements['width'].value = width;
      form.elements['height'].value = height;
      draw_preview(this, context_preview, width, height);
    },
    false
  );

  // When an image under filters is clicked, sets it as the active filter.
  filters.addEventListener(
    'click',
    function(event)
    {
      // ==========> make button visible (hidden by default)
      active_filter.src = event.target.src;
      form.elements['filter'].value = event.target.src;
    },
    false
  );

  // When form is submitted (i.e. user clicked "Take photo"),
  // sets the photo to a snapshot of the video.
  form.addEventListener(
    'submit',
    function(event)
    {
      event.preventDefault();
      context_photo.drawImage(video, 0, 0, width, height);
      form.elements['photo'].value = canvas_photo.toDataURL('image/png');
      form.submit();
    },
    false
  );
})();
