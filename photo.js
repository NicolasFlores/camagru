(function() {

	var streaming = false,
		video = document.querySelector('#video'),
		cover = document.querySelector('#cover'),
		canvas = document.querySelector('#canvas'),
		photo = document.querySelector('#photo'),
		button = document.querySelector('#button'),
		save = document.querySelector('#save'),
		width = 320,
		height = 0;

	navigator.getMedia = ( navigator.getUserMedia ||
                         navigator.webkitGetUserMedia ||
                         navigator.mozGetUserMedia ||
						   navigator.msGetUserMedia);

	navigator.getMedia(
		{
		video: true,
				audio: false
				},
		function(stream) {
			if (navigator.mozGetUserMedia) {
				video.mozSrcObject = stream;
			} else {
				var vendorURL = window.URL || window.webkitURL;
				video.src = vendorURL.createObjectURL(stream);
			}
			video.play();
		},
		function(err) {
			console.log("An error occured! " + err);
		}
		);

	video.addEventListener('canplay', function(ev){
			if (!streaming) {
				height = video.videoHeight / (video.videoWidth/width);
				video.setAttribute('width', width);
				video.setAttribute('height', height);
				canvas.setAttribute('width', width);
				canvas.setAttribute('height', height);
				streaming = true;
			}
		}, false);

	function takepicture() {
		canvas.width = width;
		canvas.height = height;
		canvas.getContext('2d').drawImage(video, 0, 0, width, height);
		var data = canvas.toDataURL('image/png');
		photo.setAttribute('src', data);
		photo.setAttribute('alt', 'photo');
		var pal = document.querySelector('#palmier');
		var sab = document.querySelector('#sabre');
		var nez = document.querySelector('#nez');
		if (pal.checked || sab.checked || nez.checked) {
			save.removeAttribute('hidden');
			save.setAttribute('value', data);
		}
	}

	button.addEventListener('click', function(ev){
			takepicture();
			ev.preventDefault();
		}, false);

})();
