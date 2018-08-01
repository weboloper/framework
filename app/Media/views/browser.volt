<!doctype html>
<html class="no-js h-100" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="manifest" href="site.webmanifest">
        <link rel="apple-touch-icon" href="icon.png">
        <!-- Place favicon.ico in the root directory -->
        <style>

		.progress-bar {
		  text-align: left;
		  transition-duration: 3s;
		}
		</style>
        {{ assets.outputCss() }}
        {{ assets.outputInlineCss() }}
    </head>
    <body class="bg-light h-100">
    	<div class="container"> 
	    	<div id="root" class="container-fluid  p-4"></div>
		</div>
	     
	    {{ assets.outputJs() }}
    	
		<script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
        {{ javascript_include('https://unpkg.com/sweetalert/dist/sweetalert.min.js') }}
        {{ javascript_include('resources/statics/js/app.ajax.js') }}

		<script type="text/javascript" language="javascript">
		$(document).on("dblclick","div.media-item",function(){
		  item_url = $(this).data("src");
		  var args = top.tinymce.activeEditor.windowManager.getParams();
		  win = (args.window);
		  input = (args.input);
		  win.document.getElementById(input).value = item_url;
		  top.tinymce.activeEditor.windowManager.close();
		});
		$(document).on("click","div.media-item",function(){
		  item_url = $(this).data("src");
		  var args = top.tinymce.activeEditor.windowManager.getParams();
		  win = (args.window);
		  input = (args.input);
		  win.document.getElementById(input).value = item_url;
		});

		var attachmentType = '{{attachmentType}}'
		</script>

		<script type="text/babel">

		    class SimpleReactFileUpload extends React.Component {

			  constructor(props) {
			    super(props);
			    this.state ={
			      file:null,
			      title: '',
			      progress : 0 
			    }
			    // this.onFormSubmit = this.onFormSubmit.bind(this)
			    this.onChange = this.onChange.bind(this)
			    this.onChangeText = this.onChangeText.bind(this)
			    this.fileUpload = this.fileUpload.bind(this)
			  }
			  // onFormSubmit(e){
			  //   e.preventDefault() // Stop form submit
			  //   var _this = this;
			  //   this.fileUpload(this.state.file).then((response)=>{
			  //     console.log(response.data);
			  //      _this.props.addFileToList(response.data); 
			  //   })
			  // }
			  onChange(e) {
			    // this.setState({file:e.target.files[0]});
			    this.setState({ progress: 0 });
			    console.log(e.target);
 			    if(e.target.files[0].size > 2000000){
	               swal({
	                  icon:  'error',
	                  text: 'File is too big! Max File size: 2MB' ,
	                  timer: 1300,
	                  buttons: false,
	                });

	               return;
	            };

			    var _this = this;
			    this.fileUpload( e.target.files[0] ).then((response)=>{
			      console.log(response.data);
			       _this.props.addFileToList(response.data); 
			    }, (error) => { 
			    	// console.log(error.response);
			    	 swal({
	                  icon:  'error',
	                  text: error.response.statusText,
	                  timer: 1300,
	                  buttons: false,
	                });
			    });

			  }
			  onChangeText(e){
			  	this.setState({ title : e.target.value });
			  }
			  updateProgressBarValue(val) {
			  	this.setState({ progress : val });
			  }
			  fileUpload(file){
			    const url = '/media/upload';
			    const formData = new FormData();
			    formData.append('file',file)
			    formData.append('title', this.state.title )
			    const config = {
			        headers: {
			            'content-type': 'multipart/form-data'
			        },
			        onUploadProgress: (progressEvent) => {
	                    if (progressEvent.lengthComputable) {
	                    	let percentCompleted = Math.floor((progressEvent.loaded * 100) / progressEvent.total);
	                       // console.log(progressEvent.loaded + ' ' + progressEvent.total);
	                       // console.log(progressEvent);

	                       this.updateProgressBarValue(percentCompleted);

	                       if(percentCompleted > 99 ){
	                       	var _this = this;
	                       		setTimeout(function(){
	                        		_this.updateProgressBarValue(0);
	                        		_this.setState({ title : '' , file: null  });
	                   			},1000);


	                       }
	                       
	                    }
           			},
           			catch: function() {
				            // console.log(error);
				    } 
			    }
			    return  axios.post(url, formData,config)
			  }

			  render() {

			  	var progressStyle = {
				  width: this.state.progress + '%'
				};

			    return (
			     <div>
			     	<div className="row">
				     <div className="col-12 col-lg-5">
				      <form onSubmit={this.onFormSubmit} className="pb-1 form-inline">
				      	<div className="form-group mx-sm-3">
				      		<input type="text" name="title" className="form-control" placeholder="{{ lang.get('media.file.placeholder') }}" onChange={this.onChangeText} value={this.state.title} autoComplete="off"/>
				      	</div>
				        <div className="upload-btn-wrapper">
					        <button className="btn btn-primary"> {{ lang.get('media.file.upload_text') }} </button>
					        <input type="file" onChange={this.onChange} />
					        
				        </div>
				        
	 			      </form>
	 			      </div>
	 			      <div className="col-lg-7 col-12 pt-2">
	 			      	{ (this.state.progress > 0  )  &&  
				        <div className="progress">
							<div className="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style={  progressStyle}  aria-valuenow={this.state.progress} aria-valuemin="0" aria-valuemax="100"></div>
						</div>}
						</div>
						</div>
						<hr/>
					</div>
			   )
			  }
			}

		   class Mediaitem extends React.Component {

		   		constructor(props) {
				    super(props);
				    this.state = { condition:  false  };
				}

				childClicked(id, i ,  event) {

			
				    this.props.setSelected(id);
				    
		 		 } 

		 
			   	render () {

			   		var classes = 'media-item col-4 col-sm-3 col-md-2  mb-4 h-100';
			   	    
			   	    var extension = this.props.item.title.split('.').pop(); 

			   	    return (<div  onClick={this.childClicked.bind(this, this.props.item.id   )} data-id={this.props.item.id} className= { this.props.selected == this.props.item.id  ? classes + " selected" :  classes} data-src={this.props.item.guid}>
			   	    		<div className='card'>
			   	    		 { this.props.item.mime_type == 'image'? 	(<div className="inner h-100"><img src={this.props.item.thumbnails.small} /></div> 
			   	    		 ) : (
			   	    		 	 <div className="inner text-center py-2 h-100"> <h2><i className="fas fa-file"></i></h2> {extension}</div> 
			   	    		 )}
			   	    		<div className="card-footer">{this.props.item.title}</div>
			   	    		</div>
			   	    </div>)
			   		
			   	}
		   }
		   class Filebrowser extends React.Component {
		   		constructor(props) {
				    super(props);
				    this.state = { posts: [] , selected : null , page : 1 , attachmentType : attachmentType  };

				    this.setSelected = this.setSelected.bind(this);
				    this.addFileToList = this.addFileToList.bind(this);
				    this.handleOnScroll = this.handleOnScroll.bind(this);

		 		}
		   		componentDidMount() {
		   			window.addEventListener('scroll', this.handleOnScroll);
				    axios.get("/media/list?type=" + attachmentType )
				      .then(res => {
				        this.setState({ posts :   res.data.data  });
				    })
				} 

				componentWillUnmount() {
				    window.removeEventListener('scroll', this.handleOnScroll);
				}

				 handleOnScroll() {
				    // http://stackoverflow.com/questions/9439725/javascript-how-to-detect-if-browser-window-is-scrolled-to-bottom
				    var scrollTop = (document.documentElement && document.documentElement.scrollTop) || document.body.scrollTop;
				    var scrollHeight = (document.documentElement && document.documentElement.scrollHeight) || document.body.scrollHeight;
				    var clientHeight = document.documentElement.clientHeight || window.innerHeight;
				    var scrolledToBottom = Math.ceil(scrollTop + clientHeight) >= scrollHeight;
				    var _this = this; 
				    if (scrolledToBottom) {
  				      axios.get("/media/list?type=" + attachmentType  + "&page=" + (this.state.page +1 ))
					      .then(res => {
					      	console.log(res.data.data);
					        _this.setState({ posts :  [...this.state.posts, ...res.data.data ]  , page : (this.state.page + 1)  });
					    })
				    }
				  } 

		 	 
				setSelected(i) {
				    this.setState({ selected: i });

				}

				addFileToList(data) {

				    axios.get("/media/list?type=" + attachmentType )
				      .then(res => {
				      	// console.log(res);
		 		        // console.log();
				        this.setState({ posts :   res.data.data  , selected: res.data.data[0].id });
				      })
				    // this.setState({ posts: [ this.state.posts, data ] });
				}
		 	 
		   		render() {
		   			var _this = this;
				    return (
				    	<div>
					    	<SimpleReactFileUpload addFileToList={this.addFileToList}></SimpleReactFileUpload>
					    	
					       <div id="media-list" className="row d-flex align-items-stretch" >
					     	{this.state.posts.map(function(item, i){
			                     // console.log(item);
			                     return <Mediaitem item={item} key={i} i={i}  setSelected={_this.setSelected }  selected={_this.state.selected } />
			                  })}
					       </div>
				       </div>
				    );
				  }


		   }	

		   ReactDOM.render(<Filebrowser/>, document.getElementById('root'));
		</script>
    </body>
</html>
