
import React from 'react';
import util from '../util';


class Item extends React.Component {

    constructor(props) {
        super(props);
        
        this.state = {isToggleOn: 'one'};
        this.handleClick = this.handleClick.bind(this);
    }


    handleClick(e) {

        e.preventDefault();

        this.setState(state => ({
          isToggleOn: 'two'
        }));
      }

    render() {


        const 
        backgroundStyle = {
            backgroundImage: 'url(' + this.props.image + ')'
            },
        pubDate = util.getDate(this.props.updated_at);

        


        return (
            <a  
                    href={'/item/' + this.props.slug} 
                    className= {this.props.image ? 'item image': 'item' } 
                    onClick={this.handleClick}
                    data-test={this.state.isToggleOn}
                    
                    >
                    

                    {this.props.image ? 
                        <div 
                        className="bg" 
                        style={backgroundStyle}
                        ></div>
                    : null }

                    <div className="text">
                        <h4 className="title">{this.props.title}</h4>
                        <h5 className="pubdate">{pubDate}</h5>
                    </div>

                
            </a>
        );


    }


}


export default Item;
