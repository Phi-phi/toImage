var wid,hei;
var str;

function toImage(){
	var canvas = document.getElementById("image");
	var jscode = document.getElementById("code").value;
	document.getElementById("code").value = "";
	str = jscode;
	var length = parseInt(jscode.length/2);
	console.log(length);
	wid = parseInt(Math.sqrt(length));
	hei = parseInt(length/wid)+1;
	var ctx = canvas.getContext('2d');
	canvas.style.width = wid+"px";
	canvas.style.height = hei + "px";
	ctx.fillStyle = 'lightgrey';
	ctx.fillRect(0,0,wid,hei);
	var imgdata = ctx.createImageData(wid, hei);
	var i = 0;
	console.log(imgdata);
	var enddata = false;
	loop1st:
	for(var i = 0; i < length; ++i){
		for(var x = 0; x < 4; ++x){
			if(x < 2) imgdata.data[i * 4 + x] = jscode.charCodeAt(i * 2) * 2;
			else imgdata.data[i * 4 + x] = jscode.charCodeAt(i * 2 + 1) * 2;
			if(imgdata.data[i * 4 + x] == 0 || isNaN(imgdata.data[i * 4 + x])) {
				enddata = true;
				console.log("end");
				break loop1st;
			}
		}
	}
	console.log(imgdata.data);
	imgdata.data[length * 4 + 1] = 2;
	ctx.putImageData(imgdata,0,0);
}

function Imageto(){
	var canvas = document.getElementById("image");
	var length = str.length;
	var code = [];
	var ctx = canvas.getContext('2d');
	var imgdata = ctx.getImageData(0,0,wid,hei).data;
	//console.log(imgdata);
	var imglength = imgdata.length / 2;
	console.log(imglength);
	loop1:
	for(var i = 0; i < imglength; i+=2){
		var codenum = [];
		for(var x = 0; x < 4; ++x){
			codenum[x] = parseInt(imgdata[i * 2 + x]);
			if(codenum[x] == 2) break;
		}
		var codedata;
		for(var x = 0; x < 2; ++x){
			//console.log(codenum);
			var code1 = codenum[x * 2], code2 = codenum[x * 2 + 1];
			//console.log("code1,2",code1, code2);
			if(code1 != code2){
				if(code1%2 != 0 && code2%2 != 0){
					if(code1 > code2){
						codedata = String.fromCharCode(parseInt(code1 / 2));
					}else if(code1 < code2){
						codedata = String.fromCharCode(parseInt(code2 / 2));
					}else{
						codedata = String.fromCharCode(parseInt((code1 + 2) / 2));
					}
				}else if(code1%2 == 0){
					codedata = String.fromCharCode(parseInt(code1 / 2));
				}else{
					codedata = String.fromCharCode(parseInt(code2 / 2));
				}
			}
			codedata = String.fromCharCode(parseInt(code1 / 2));
			//console.log(code1,code2, String.fromCharCode(parseInt(code1 / 2)), String.fromCharCode(parseInt(code2 / 2)));
			code.push(codedata);
			if(isNaN(code1) || isNaN(code2) || code1 == 0 || code2 == 0) break loop1;
		}

	}
		console.log(code);
	document.getElementById("code").value = code.join('');
	ctx.clearRect(0, 0, canvas.width, canvas.height);
}

