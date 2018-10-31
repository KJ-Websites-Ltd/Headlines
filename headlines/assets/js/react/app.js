
import React from 'react';
import ReactDOM from 'react-dom';
import util from './util';

import Item from './component/Item';


//For seo I render the layout using PHP which can be read, this needs cleaning out
const 
    docBody = document.body,
    rootElement = document.getElementById('root');


class App extends React.Component {

    constructor() {
        super();

        this.state = {
            entries:  []
        }
    };

    componentDidMount() {
        fetch('http://headlinesapi.kjwebsites.co.uk/api/')
        .then(response => response.json())
        .then(entries => {

            //docBody.innerHTML = '';
            //docBody.appendChild(rootElement);

            this.setState({
                entries
            });
        });

    }

    render() {

        return (
            <nav className="nav items grid">
                {this.state.entries.map(

                    ({id, title, updated_at, image, slug}) => (

                        <Item
                            key={id}
                            title={title}
                            image={image}
                            updated_at={updated_at}
                            slug={slug}
                        ></Item>
                    )
                )}

            </nav>
        )

    }


}




ReactDOM.render(<App />, rootElement);
