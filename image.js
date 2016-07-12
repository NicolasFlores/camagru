function setimage(i) {
	var photo = document.querySelector("#photo");
	var save = document.querySelector("#save");

	photo.setAttribute('src', i);
	photo.setAttribute('alt', 'image upload');
	var pal = document.querySelector('#palmier');
	var sab = document.querySelector('#sabre');
	var nez = document.querySelector('#nez');
	if (pal.checked || sab.checked || nez.checked) {
		save.removeAttribute('hidden');
		save.setAttribute('value', i);
	}
}
