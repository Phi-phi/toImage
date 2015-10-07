var wid,hei;
var str;
var num;

function toImage(){
	console.time("time");
	var canvas = document.getElementById("image");
	var jscode = document.getElementById("code").value;
	document.getElementById("code").value = "";
	str = jscode;
	var length = parseInt(jscode.length/3);
	console.log(length);

	wid = parseInt(Math.sqrt(length)) + 1;
	hei = wid;
	canvas.style.width = wid+"px";
	canvas.style.height = hei + "px";

	var ctx = canvas.getContext('2d');

	var imagedata = ctx.createImageData(wid, hei);
	var i = 0;
	console.log(wid, hei, "start",imagedata);
	var enddata = false;
	var total = wid * hei;
	loop1st:
	for(var y = 0; y < imagedata.height; y++) {
		for(var x = 0; x < imagedata.width; x++) {
			imagedata.data[(y * imagedata.width + x) * 4 + 0] = 255 - jscode.charCodeAt((y * imagedata.width + x) * 3 + 0); // 赤 (R)
			imagedata.data[(y * imagedata.width + x) * 4 + 1] = 255 - jscode.charCodeAt((y * imagedata.width + x) * 3 + 1);
			imagedata.data[(y * imagedata.width + x) * 4 + 2] = 255 - jscode.charCodeAt((y * imagedata.width + x) * 3 + 2);
			imagedata.data[(y * imagedata.width + x) * 4 + 3] = 0xFF; // 不透明度 (A)
		}
	}
	console.log(imagedata.data);
	imagedata.data[length * 4 + 1] = 3;
	ctx.putImageData(imagedata,0,0);
	console.timeEnd("time");
}

function Imageto(){
	console.time("time2");
	var canvas = document.getElementById("image");
	var length = str.length;
	var code = [];
	var ctx = canvas.getContext('2d');
	var imgdata = ctx.getImageData(0,0,wid,hei);
	//console.log(imgdata);
	for(var y = 0; y < imgdata.height; y++) {
		for(var x = 0; x < imgdata.width; x++) {
			var one = 255 - imgdata.data[(y * imgdata.width + x) * 4 + 0];
			var two = 255 - imgdata.data[(y * imgdata.width + x) * 4 + 1];
			var three = 255 - imgdata.data[(y * imgdata.width + x) * 4 + 2];
			code.push(String.fromCharCode(one));
			code.push(String.fromCharCode(two));
			code.push(String.fromCharCode(three));
		}
	}

	console.log(code);
	document.getElementById("code").value = code.join('');
	console.timeEnd("time2");
	ctx.clearRect(0, 0, canvas.width, canvas.height);
}

