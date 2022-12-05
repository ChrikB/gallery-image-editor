<!doctype html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <title>Minimalistic Gallery Vue.js with Fabric.js</title>
  <meta name="keywords"    content="vue.js fabric.js, vue with canvas, canvas gallery">
  <meta name="description" content="Binding vue template with fabric canvas">
  <meta name="author"      content="Chris B">
  <script type="text/javascript" src="libs/vue.global.prod.js"></script>
  <script type="text/javascript" src="libs/fabric.min.js"></script>
  <script type="text/javascript" src="libs/fontfaceobserver.js"></script>
  <script type="text/javascript" src="libs/moment.js"></script>
  <script type="text/javascript" src="bootstrap/bootstrap.min.js"></script>
  <link   rel="stylesheet" href="bootstrap/bootstrap.min.css" />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Lobster&family=Permanent+Marker&display=swap');
  </style>
  <style>
    /* footer fit to bottom */
    body,html{
      width:100%;
      height:100%;
    }
    #wrapper {
      min-height: 800px;
      min-height: calc(100vh - 100px);
    }
    .footer_wrapper{
      margin-top: 0; /* take available whitespace */
      margin-bottom: -10px;      /* remove bottom whitespace */
      padding-bottom: 5px;
      height: 100px;  
      display: flex;
      align-items: center;
      justify-content: center;
      align-content: space-around;
      flex-direction: column;
    }
    .footer_wrapper .footer{
      margin: 0;
    }


    .soc-check, .soc-check>*{
      cursor: pointer;
    } 
    .socials .form-check>div { 
        width: 100px;
    }
    .socials span{
      margin-left: 20px;
    }
    .socials .form-check span input{
      height: 22px;
    }
    .flex-column{
      max-width: 200px;
    }
   

    .canvas-container {
      transform: scale(1);
      transition: transform 0.5s;
    }
    .gallery .canvas-container:hover{
      transform: scale(0.92);
    }

    .canvas-container .btn{
      transition: background-color 0.5s;
    }    
    .canvas-container:hover .btn{
      background-color: orangered;
      border-color: green;
    }

    h3{
      font-family: 'Lobster';
    }
    form>*{
      font-family: 'Lobster';
    }
    form button[type="submit"]{
      margin-bottom: 20px;
    }

    /*
    .responsive-canvas{
      width: 100%;
      padding-bottom: 50%;
      position: relative;
    }
    .responsive-canvas .canvas-container,
    .responsive-canvas canvas {
      width: 100%!important;
      height:  100%!important;
      position: absolute!important;
    }
    */

    h3.project-title {
      text-align: center;
      padding-top: 20px;
      padding-bottom: 20px;
    }


    h3.project-title span {
      background: 
        linear-gradient(to right, rgba(100, 200, 200, 1), rgba(100, 200, 200, 1)),
        linear-gradient(to right, rgba(255, 0, 0, 1), rgba(255, 0, 180, 1), rgba(0, 100, 200, 1));
      background-size: 100% 0.1em, 0 0.1em;
      background-position: 100% 100%, 0 100%;
      background-repeat: no-repeat;
      transition: background-size 400ms;
      animation: 4s ease-out 0s 1 underlineAnim forwards;
    }

    @keyframes underlineAnim {
        0% {
          background-size: 100% 0.1em, 0 0.1em;
        }
        100% {
          background-size: 0 0.1em, 100% 0.1em;
        }
    }


    /* spinner */
    .spinner-ring {
      display: inline-block;
      position: relative;
      width: 80px;
      height: 80px;
      left: 50%;
      top: 50%;
      transform: translate(-50%, -50%);
    }
    .spinner-ring div {
      box-sizing: border-box;
      display: block;
      position: absolute;
      width: 64px;
      height: 64px;
      margin: 8px;
      border: 8px solid #fff;
      border-radius: 50%;
      animation: spinner-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
      border-color: #6b0641 transparent transparent transparent;
    }
    .spinner-ring div:nth-child(1) {
      animation-delay: -0.45s;
    }
    .spinner-ring div:nth-child(2) {
      animation-delay: -0.3s;
    }
    .spinner-ring div:nth-child(3) {
      animation-delay: -0.15s;
    }
    @keyframes spinner-ring {
      0% {
        transform: rotate(0deg);
      }
      100% {
        transform: rotate(360deg);
      }
    }



  </style>
</head>

<body>
  <div class="container-fluid" id="wrapper">
    <div class="row">
      <div class="col-12"><h3 class="project-title"><span>Playing with Vue.js + Canvas(with Fabric.js)</span></h3></div>
      <div class="gallery  col">
        <h3 class="text-center mb-4">Gallery</h3>
        <div class="d-flex flex-wrap justify-content-center">
          <div class="d-flex flex-row flex-wrap justify-content-center align-items-center" id="gallery-canvases"></div>
        </div>    
      </div>
    </div>
  </div>
  <div class="footer_wrapper" style="color: red; font-size: 20px; text-align: center; margin-top: 20px;">
    <p class="footer"><?php include '../../php_template/footer.php'; echo "and Chris B" ?> </p>
  </div>
  <!-- Modal -->
  <div class="modal fade" id="editorModal" tabindex="-1" aria-labelledby="editorModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editorModalLabel"><h3 class="text-center">Editor</h3></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col" id="template-cont"></div>
                    <div class="col-12 col-xs-12  col-sm-12 col-md-12 col-lg-6 col-xl-6">
                        <div class="responsive-canvas" style="overflow: hidden;">
                          <canvas id="canvas" width="400" height="520"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <h6 class="text-start w-100">Coded By Chris B</h6>
        </div>
      </div>
    </div>
  </div>
</body>


<script>

let GAL;
onload = function(){
    GAL = new Gallery();
}


class Gallery {
  constructor(gallery) {
    if(gallery){
      this.gallery = gallery;
    }else {
      this.gallery = [];
    }
    this._gallery = [];

    this.galleryLoad();
    this.galleryRender();
    this.loading = 0;
  }

  galleryLoad(){
      /* hardcoded models but it could be retrieved from server, db or whatever */
      this.gallery = [
        {
          id: 1,
          uploader: 'Libico',
          imgUrl: 'https://upload.wikimedia.org/wikipedia/commons/5/5e/Deserto_libico_-_Driving_-_panoramio.jpg',
          /* https://en.wikipedia.org/wiki/Desert#/media/File:Deserto_libico_-_Driving_-_panoramio.jpg */
          dateUploaded: "2022-11-21",
          socials: {
            fb: true,
            fbLikes: 165,     
            insta: true,
            instaLikes: 15,
            tw:  true,
            twLikes: 16
          },
          likes: 50000,
          itsAdv: false
      },
      {
          id: 2,
          uploader: 'Kau Desert',
          imgUrl: 'https://upload.wikimedia.org/wikipedia/commons/9/95/Kau_desert.jpg',
          dateUploaded: "2022-10-11",
          socials: {
            fb: true,
            fbLikes: 165,     
            insta: true,
            instaLikes: 15,
            tw:  true,
            twLikes: 16
          },
          likes: 50000,
          itsAdv: false
      },
      {
          id: 3,
          uploader: 'Wave',
          imgUrl: 'https://upload.wikimedia.org/wikipedia/commons/5/55/Large_breaking_wave.jpg',
          dateUploaded: "2022-05-21",
          socials: {
            fb: true,
            fbLikes: 165,     
            insta: true,
            instaLikes: 15,
            tw:  true,
            twLikes: 16
          },
          likes: 50000,
          itsAdv: false
      },
      {
          id: 4,
          uploader: 'Jungle',
          imgUrl: 'https://upload.wikimedia.org/wikipedia/commons/4/47/Jungle.jpg', /* https://commons.wikimedia.org/wiki/File:Jungle.jpg */
          dateUploaded: "2021-10-11",
          socials: {
            fb: true,
            fbLikes: 165,     
            insta: true,
            instaLikes: 15,
            tw:  true,
            twLikes: 16
          },
          likes: 50000,
          itsAdv: false
      }          
    ];
 
  }

  
  galleryRender(){ 
    let  THAT = this;
    for (let i =0; i<this.gallery.length; i++){ 
      this.loading++; /* start loading counter */

      let c = document.createElement('canvas');
      c.setAttribute('width', 500);
      c.setAttribute('height', 600);
      document.getElementById('gallery-canvases').appendChild(c);

      /* adding 'edit' button and inject it to each gallery slot  */
      let editBtn = document.createElement('button');

      let modelToCanvas =  new ModelCanvas(this.gallery[i], c, function(t){
        editBtn.style.display = 'none';
        editBtn.style.position = 'absolute';
        editBtn.setAttribute('class', 'btn btn-danger');
        editBtn.innerHTML = 'Edit';
        t.F.wrapperEl.appendChild(editBtn);

        editBtn.addEventListener('click',function(){ 
        

          document.querySelector('#editorModal').addEventListener('show.bs.modal', function (event) {
            /* if editor has another image already, clean first before load the new */
            if (THAT.editor){
              THAT.editor.modelToCanvas.F.clear();
            }  
          });
          document.querySelector('#editorModal').addEventListener('shown.bs.modal', function (event) {
            /* load canvas after modal shown, so modal's width is not zero */
            THAT.editor =  new VueFabric(THAT.gallery[i]);  
          });
          /* when modal is closed we update the gallery canvas */
          document.querySelector('#editorModal').addEventListener('hidden.bs.modal', function (event) {
            THAT._gallery[i].render();
          });                      
          /* show modal on 'edit' button click*/
          bootstrap.Modal.getOrCreateInstance(document.querySelector('#editorModal')).show();
        });
        
      }, function(){
        editBtn.style.display = '';
      }); 
      this._gallery[i] = modelToCanvas;

      modelToCanvas.render();


    }
  }
  
}








class VueFabric {

  constructor(model) {
    if(model){
      this.model = model;
    } else {
      this.model =  {
          uploader: 'default',
          imgUrl: 'https://upload.wikimedia.org/wikipedia/commons/b/b0/Okmulgee_State_Park_Good_Morning_Sunshine.jpg',
          dateUploaded: "2022-10-21",
          socials: {
            fb: true,
            fbLikes: 165,     
            insta: true,
            instaLikes: 15,
            tw:  true,
            twLikes: 16
          },
          likes: 50000,
          itsAdv: false
      };
    }

    this.init();
  }
  

  init(){
    this.initVue();
  }


  initVue(){
    let THAT = this;
    this.V = Vue.createApp({
      data() {
        return THAT.model;
      },
      template:`
    <form class="form-fields">
      <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Uploader</label>
        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"  v-model="uploader"  @change="renderFabric" maxlength="12">
      </div>
      <div class="mb-3">
        <label for="formFile" class="form-label">Image</label>
        <input class="form-control" id="formFile" v-model="imgUrl" disabled>
        <div id="passwordHelpBlock" class="form-text d-none">
          Only jpg and png allowed
        </div>
      </div>
      <div class="mb-3 socials">
        <label for="exampleInputEmail1" class="form-label">Social Popularity:</label>
        <div style="max-width:400px;">
          <div class="form-check">
            <div class="d-inline-block soc-check">
              <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault1" v-model="socials.fb"  @change="renderFabric">
              <label class="form-check-label" for="flexCheckDefault1">
                Facebook
              </label>
            </div>
            <span class="d-inline-block" v-if="socials.fb===true">Likes: <input   v-model="socials.fbLikes"  @change="renderFabric"></span>
          </div>
          <div class="form-check">
            <div class="d-inline-block soc-check">
              <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked2" v-model="socials.insta"  @change="renderFabric">
              <label class="form-check-label" for="flexCheckChecked2">
                Instagram
              </label>
            </div>
            <span  class="d-inline-block" v-if="socials.insta===true">Likes: <input   v-model="socials.instaLikes"  @change="renderFabric"></span>
          </div>
          <div class="form-check">
            <div class="d-inline-block soc-check">
              <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked3" v-model="socials.tw"  @change="renderFabric">
              <label class="form-check-label" for="flexCheckChecked3">
                Twitter
              </label>
            </div>
            <span  class="d-inline-block"  v-if="socials.tw===true">Likes: <input   v-model="socials.twLikes"  @change="renderFabric"></span>
          </div>
        </div>
      </div>
      <div class="row">            
        <div class="mb-3 col-6">
          <label for="labelForLikes" class="form-label">Maximum Likes</label>
          <select class="form-select" aria-label="10" v-model="likes" @change="renderFabric" id="labelForLikes">
            <option value="150">Less than 150</option>
            <option value="300">150-300</option>
            <option value="600">300-600</option>
            <option value="1500">600-1500</option>
            <option value="50000">50000 or more</option>
          </select>
        </div>                    
        <div class="mb-3  col-6">
          <label for="startDate" class="form-label">Image Date</label>
          <input id="startDate" class="form-control" type="date" v-model="dateUploaded"  @change="renderFabric"/>
        </div>
      </div>
    </form>
      `,
      methods: {
        renderFabric() {
          THAT.modelToCanvas.render(THAT.modelToCanvas.bounds);
        },
        handleWindowResize(){
          let parentWidth = document.querySelector('.responsive-canvas').offsetWidth; 
          THAT.modelToCanvas.render({ left:0, top:0, width: parseInt(parentWidth,10), height: 600 });
        }
      },
      created(){
          let cav = document.querySelector('#canvas');
          THAT.modelToCanvas =  new ModelCanvas(THAT.model, cav);      
      },
      mounted(){
          this.handleWindowResize();
          window.addEventListener('resize', this.handleWindowResize,  false);
      },
      unmounted(){
          window.removeEventListener('resize', this.handleWindowResize, false);
      }
    });

    this.V.mount("#template-cont");
  }

}







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



</script>
</html>