/*
   Handles canvas rendering with fabric.js
*/

class ModelCanvas{
	constructor(model, canvas, onInit, onRender) {
		this.model = model;
		if (!canvas){
		  this.canvas = document.createElement('canvas');
		}else{
		  this.canvas = canvas;
		}
		this.canvasWidthOrig = '';
		this.canvasHeightOrig = '';
		if (onRender){
			this.onRender = onRender; 
		}
		this.F = new fabric.Canvas(this.canvas, { selection: false });
	    // this.render();
	    if (onInit){
		  onInit(this);
	    }
	}
  
  
	calculateAspectRatioFit(srcWidth, srcHeight, maxWidth, maxHeight) {
	  let ratio = Math.min(maxWidth / srcWidth, maxHeight / srcHeight);
  
	  return { width: srcWidth*ratio, height: srcHeight*ratio };
	}
  
	hasSpinner(parentCont){
	  if (!parentCont.querySelector(".spinner-ring")){
		return false;
	  }
	  return true;
	}
	addSpinner(parentCont){
	  let spinner = document.createElement('div');
	  spinner.setAttribute('class', "spinner-ring");
	  spinner.style.display = 'none';
	  spinner.innerHTML = '<div></div><div></div><div></div><div></div>';
	  parentCont.append(spinner);
	}
	showSpinner(parentCont){
	  this.canvas.parentNode.querySelector(".spinner-ring").style.display = 'block';
	}
	hideSpinner(parentCont){
	  this.canvas.parentNode.querySelector(".spinner-ring").style.display = 'none';
	}  
  
	render(bounds){
  
	  let THAT = this;
	  if(!bounds) {
		  bounds = {
			left:0,
			top:0,
			width: 400,
			height: 500
		  };
	  }
  
	  if (this.model.imgUrl) {
		if (!THAT.hasSpinner(THAT.canvas.parentNode)){ 
		  THAT.addSpinner(THAT.canvas.parentNode); /* add spinner */
		}
		THAT.showSpinner(THAT.canvas.parentNode);
  
		let canvas = THAT.F; 
		canvas.clear(); 
  
		let url = this.model.imgUrl;
		fabric.Image.fromURL(url, function(img) {
		  canvas.clear();
		  /* store original img width and height */
		  THAT.canvasWidthOrig = img.width;
		  THAT.canvasHeightOrig = img.height;
  
		  if ((bounds.height / bounds.width) >= (img.height / img.width)) {
			img.scaleToHeight(bounds.height);
			img.set({
			  top: bounds.top,
			  left: (bounds.left + (bounds.width/2)) - (img.getBoundingRect().width/2)
			});
		  }
		  else {
			img.scaleToWidth(bounds.width);
			img.set({
			  top: (bounds.top + (bounds.height/2)) - (img.getBoundingRect().height/2),
			  left: bounds.left
			});
		  }
		  canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
			  top: 0,
			  left:  0
		  });
		  canvas.setHeight(bounds.height);
		  canvas.setWidth(bounds.width);
		  THAT.bounds = bounds;/* store current bounds */
		  THAT.onBgReady();
		 
		});      
	  }
	}
  
	onBgReady(){      
	  this.renderAuthorMeta();
	  this.renderLikes();
	  this.renderViewPie();
	  if (this.onRender){
		  this.onRender(this); 
	  }
	  this.hideSpinner(this.canvas.parentNode);
	}
  
	renderAuthorMeta(){
  
	  let THAT = this;
	  let canvas = THAT.F;
	  let font = "Lobster";
  
	  let shadow = new fabric.Shadow({
		  color: 'black',
		  blur: 15
	  });
		
	  /* date with smaller font than 'uploader' */
	  let styles = {1:{}};
	  for(let k=0;k<12;k++){
		styles[1][k] = {fontSize: 15};
	  }
  
	  /* convert date using moment.js */
	  let dateConvert = moment(this.model.dateUploaded).format("DD-MM-YYYY");
  
	  let textbox = new fabric.Textbox( this.model.uploader +'\n '+ dateConvert, {
		left: 50,
		top: 50,
		width: 150,
		fontSize: 50,
		textAlign: "center",
		fill: 'rgba(255,255,255,0.6)',
		shadow: shadow,
		styles: styles,
		selectable: false,
		evented: false
	  });
	  THAT.F.add(textbox);
	  textbox.center();
	  textbox.set({top: canvas.height*0.2 });
	  /* load the google font */
	  let myfont = new FontFaceObserver(font);
	  myfont.load()
	  .then(function() {
		textbox.set("fontFamily", font);
		canvas.requestRenderAll();
	  }).catch(function(e) {
		console.log(e, 'font loading failed ' + font);
	  });
	}
  
  
	renderLikes(){
	  
	  let THAT = this;
	  let canvas = THAT.F;
	  let font2 = "Permanent Marker";
	  
	  let rect = new fabric.Rect({
		  left: 0,
		  top: canvas.height/2,// + canvas.height/12 ,
		  width: canvas.width,
		  height: canvas.height/2,
		  fill: 'rgba(0,0,0,0.5)',
		  stroke: 'black',
		  strokeWidth: 1,
		  selectable: false,
		  evented: false
	  });
	  canvas.add(rect);
	  
	  
	  let textbox2 = new fabric.Textbox(this.model.likes+ ' Likes!!!' , {
		left: 50,
		top: canvas.height/2 + canvas.height/4 ,
		width: 150,
		fontSize: 20,
		fill: 'white',
		textAlign: "center",
		selectable: false,
		evented: false
	  });
	  THAT.F.add(textbox2);
	  textbox2.center();
	  textbox2.set({top: (canvas.height/2 + canvas.height/8) })
	  
	  let myfont = new FontFaceObserver(font2);
	  
	  myfont.load()
	  .then(function() {
		textbox2.set("fontFamily", font2);
		canvas.requestRenderAll();
	  }).catch(function(e) {
		console.log(e, 'font loading failed ' + font);
	  });
	}
  
  
	renderViewPie(){
	  let THAT = this;
	  let canvas = THAT.F;
  
	  let fbLikes = THAT.model.socials.fb? parseInt(THAT.model.socials.fbLikes,10) : 0;        
	  let instaLikes = THAT.model.socials.insta? parseInt(THAT.model.socials.instaLikes,10) : 0;
	  let twLikes = THAT.model.socials.tw? parseInt(THAT.model.socials.twLikes,10) : 0;     
	  let viewSum = fbLikes + instaLikes + twLikes;
  
	  let socialPercent = {
		fb: fbLikes*360/ viewSum , 
		inst: instaLikes*360/ viewSum , 
		tw: twLikes*360/ viewSum 
	  }; 
  
	  let colors = [
		 '#3b5998',
		 '#bc2a8d',
		 '#1DA1F2'
	  ];
  
	  let circles = [];
	  let angleSum = 0;
	  let index = 0;
	  for(let i in socialPercent){
		let startAngle;
		let endAngle;
		angleSum += socialPercent[i];
		if(index===0){
		  startAngle = 0;
		}else {
		  startAngle = angleSum - socialPercent[i];
		}
		if(index==2){ 
		  endAngle = 360;
		}else {
		  endAngle = angleSum;
		}
  
		let c = new fabric.Circle({
		  radius: 40,
		  left: 18,
		  top: 30,
		  angle: 0,
		  startAngle: startAngle,
		  endAngle: endAngle,
		  stroke: colors[index],
		  strokeWidth: 25,
		  textAlign: "center",
		  fill: ''
		});
		index++;
		circles.push(c); 
	  }
  
	  let textbox = new fabric.Textbox( 'Social Popularity', {
		left: 0,
		top: 0,
		width: 140,
		fontSize: 20,
		fill: 'rgba(255,255,255,1)',
		selectable: false
	  });
	  let font = "Lobster";
	  let myfont = new FontFaceObserver(font);
	  myfont.load()
	  .then(function() {
		textbox.set("fontFamily", font);
		canvas.requestRenderAll();
	  }).catch(function(e) {
		console.log(e, 'font loading failed ' + font);
	  });
  
	  circles.push(textbox);
  
	  var group = new fabric.Group(circles);
	  group.set({
		top:(canvas.height-150), 
		left: (canvas.width-150),
		selectable: false,
		evented: false
	  });
	  canvas.add(group);
	}
}
  