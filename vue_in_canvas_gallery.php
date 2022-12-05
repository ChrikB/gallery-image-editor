<!doctype html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <title>Minimalistic Gallery with Vue.js and Fabric.js</title>
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
  <script type="text/javascript" src="js/main.js"></script>
  <script type="text/javascript" src="js/editor.js"></script>
  <script type="text/javascript" src="js/canvas.js"></script>
  <script>
    let GAL;
    onload = function(){
      GAL = new Gallery();
    }
  </script>
</body>
</html>