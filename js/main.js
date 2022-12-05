/*
   Main class. 
   Iterates array of objects and initiates vue and fabric for each object
   Data are hardcoded for demo's purposes.

*/
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
			likes: 300,
			itsAdv: false
		},
		{
			id: 2,
			uploader: 'Kau Desert',
			imgUrl: 'https://upload.wikimedia.org/wikipedia/commons/9/95/Kau_desert.jpg',
			dateUploaded: "2022-10-11",
			socials: {
			  fb: true,
			  fbLikes: 105,     
			  insta: true,
			  instaLikes: 55,
			  tw:  true,
			  twLikes: 156
			},
			likes: 1500,
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
			  instaLikes: 150,
			  tw:  true,
			  twLikes: 16
			},
			likes: 600,
			itsAdv: false
		},
		{
			id: 4,
			uploader: 'Jungle',
			imgUrl: 'https://upload.wikimedia.org/wikipedia/commons/4/47/Jungle.jpg', /* https://commons.wikimedia.org/wiki/File:Jungle.jpg */
			dateUploaded: "2021-10-11",
			socials: {
			  fb: true,
			  fbLikes: 16,     
			  insta: true,
			  instaLikes: 35,
			  tw:  true,
			  twLikes: 30
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
  
		let c = document.createElement('canvas');/* no need for jquery. DOM manipulation is not much. */
		c.setAttribute('width', 500);
		c.setAttribute('height', 600);
		document.getElementById('gallery-canvases').appendChild(c);
  
		/* adding 'edit' button and inject it to each gallery slot  */
		let editBtn = document.createElement('button');
  
		/* create an overlay infront of canvas for touch scrolling */
		let overlay = document.createElement('div');
		overlay.setAttribute('style', 'position:absolute;width:100%;height:100%;background: rgba(0,0,0,0.01);opacity:0.01;z-index:1;');

		let modelToCanvas =  new ModelCanvas(this.gallery[i], c, function(t){
		  editBtn.style.display = 'none';
		  editBtn.style.position = 'absolute';
		  editBtn.style.zIndex = 2;
		  editBtn.setAttribute('class', 'btn btn-danger');
		  editBtn.innerHTML = 'Edit';
		  t.F.wrapperEl.appendChild(editBtn);
		  t.F.wrapperEl.appendChild(overlay);

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
  
  
  