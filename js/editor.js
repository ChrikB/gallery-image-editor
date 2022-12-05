
/*
    Small vue component with basic methods.
	It handles modal's editor form data and updates gallery canvas.
	Gallery array updates using 2-way data binding
*/

class VueFabric {

	constructor(model) { 
	  /*  model arg passed from gallery class */
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
  
  