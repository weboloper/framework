{{ assets.outputCss() }}
{{ assets.outputJs() }}
{{ assets.outputInlineCss() }}

<div id="field_name" class="media-item selected" data-id="4">a</div>
<div id="root" class="container-fluid"></div>
<script type="text/babel">

   class Mediaitem extends React.Component {

   		constructor(props) {
		    super(props);
		    this.state = { condition:  false  };
		}

		childClicked(id, i ,  event) {

	
		    this.props.setSelected(id);
		    
 		 } 

 
	   	render () {
	   		if(this.props.item.mime_type == 'images') {
	   			return (<div className="media-item">
	   			<img src={this.props.item.thumbnails.small} />
	   			<strong>{this.props.item.title}</strong>
	   			</div>)
	   		} 
	   	    
	   	    var extension = this.props.item.title.split('.').pop(); 

	   	    return (<div  onClick={this.childClicked.bind(this, this.props.item.id   )} data-id={this.props.item.id} className= { this.props.selected == this.props.item.id  ? "media-item selected" : "media-item" }>
	   	    		<div className="inner"><span> {extension}</span></div>
	   	    		<div className="footer">{this.props.item.title}</div>
	   	    </div>)
	   		
	   	}
   }
   class Filebrowser extends React.Component {
   		constructor(props) {
		    super(props);
		    this.state = { posts: [] , selected : null  };

		    this.setSelected = this.setSelected.bind(this);

 		}
   		componentDidMount() {
		    axios.get("/media/list")
		      .then(res => {
		      	// console.log(res);
 		        // console.log(res.data.data);
		        this.setState({ posts :   res.data.data  });
		      })
		  } 

 	 

		  setSelected(i) {
		    this.setState({ selected: i });

		  }
 	 
   		render() {
   			var _this = this;
		    return (
		       <div id="media-list" className="row" >
		     	{this.state.posts.map(function(item, i){
                     // console.log(item);
                     return <Mediaitem item={item} key={i} i={i}  setSelected={_this.setSelected }  selected={_this.state.selected } />
                  })}
		       </div>
		    );
		  }


   }	

   ReactDOM.render(<Filebrowser/>, document.getElementById('root'));
</script>